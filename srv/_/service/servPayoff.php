<?php


namespace _;


use Core\library\Mysql;
use Core\library\Payinit;
use Core\unitInstance;
use Core\unitResult;

class servPayoff extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function overview($uid)
    {
        $data = dataUserKeep::sole($this->platform)->getPayoff($uid);
        $data['gross'] /= 100;
        $data['remain'] /= 100;
        $data['expect'] /= 100;
        return $data;
    }

    public function calcRatio($lessonId, $originId)
    {
        $tuid = servLesson::sole($this->platform)->id2tuid($lessonId);
        $tusn = servUser::sole($this->platform)->uid2usn($tuid);
//        $oid = servOrigin::sole($this->platform)->trim(['origin_id'=>$originId], 2);
//        $key = dataOrigin::sole($this->platform)->fetchOne(['origin_id'=>$oid], ['key'], 0);
        $origin = servOrigin::sole($this->platform)->chop(['id' => $originId], 2);
        $fee = 0.01;
        if ($origin[0]['key'] == 'teacher' && $origin[1]['key'] == $tusn) {
            $share = 1;
        } else {
            $share = 0.5;
        }
        return (1 - $fee) * $share;
    }

    public function orderPayoff($order)
    {
        $tuid = servLesson::sole($this->platform)->id2tuid($order['lesson_id']);
        $amount = $order['order_amount']; //以订单价格作为分成计算基础
        $_commission = 0; // 扣除讲师或出品人的佣金

        if ($order['extra']['series'] ?? null) { // 系列课
            $suid = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $order['extra']['series']], ['uid'], 0);
            $unionOrder = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $order['pay_sn']], ['order_amount', 'extra']);
            $psn = $unionOrder['extra']['promote'] ?? null;
            if ($psn) { // 使用优惠券
                $promoteInfo = servPromote::sole($this->platform)->info($psn);
                // 计算本次单课在系列课中的价格比重
                $ratio = $unionOrder['extra']['cost']['total'] ? ($order['extra']['price'] / $unionOrder['extra']['cost']['total']) : 1;
                if ($promoteInfo['payoff']) {
                    return $promoteInfo['payoff'] * $ratio;
                }
                if (in_array($promoteInfo['i_type'], [
                        dataPromote::TYPE_VOUCHER,
                        dataPromote::TYPE_HAGGLE,
                        dataPromote::TYPE_REWARD,
                    ]) && $promoteInfo['uid'] != $suid
                    && $promoteInfo['uid'] != $tuid) { // 抵用券计算分成是需补上折扣
                    $amount += $promoteInfo['discount'] * $ratio;
                }
                if ($promoteInfo['uid'] == $suid || $promoteInfo['uid'] == $tuid) { // 计算课程分成时扣除佣金
                    $_commission = $promoteInfo['commission'] * $ratio;
                }
            }
            $tPayoff = $this->calcPayoff($tuid, $amount, $order['origin_id']) - $_commission;
            $sPayoff = $this->calcPayoff($suid, $amount, $order['origin_id']) - $_commission;
            return floor(max($tPayoff, $sPayoff));
        } else {
            $psn = $order['extra']['promote'] ?? null;
            if ($psn) {
                $promoteInfo = servPromote::sole($this->platform)->info($psn);
                if ($promoteInfo['payoff']) {
                    return $promoteInfo['payoff'];
                }
                if (in_array($promoteInfo['i_type'], [
                        dataPromote::TYPE_VOUCHER,
                        dataPromote::TYPE_HAGGLE,
                        dataPromote::TYPE_REWARD,
                    ]) && $promoteInfo['uid'] != $tuid) { // 抵用券计算分成是需补上折扣
                    $amount += $promoteInfo['discount'];
                }
                if ($promoteInfo['uid'] == $tuid) {
                    $_commission = $promoteInfo['commission']; // 优惠券分销者和讲师为同一人时，计算扣除分销佣金
                }
            }
            $tPayoff = $this->calcPayoff($tuid, $amount, $order['origin_id']) - $_commission;
            return $tPayoff;
        }
    }

    public function seriesPayoff()
    {
    }

    public function calcPayoff($uid, $amount, $originId)
    {
        $fee = 0.01;
        $tusn = servUser::sole($this->platform)->uid2usn($uid);
        $origin = servOrigin::sole($this->platform)->chop(['id' => $originId], 2);
        if (isset($origin[1]) && $origin[1]['key'] == $tusn) {
            $share = 1;
        } else {
            $share = 0.5;
        }
        if ($uid == 261 && $origin[0]['key'] == 'promote') {
            //写死亦仁分销渠道分成
            $feeAmount = $amount * $fee;
            if (in_array($originId, [38, 28, 138, 28, 104, 104, 166, 172, 134, 29, 170, 114, 166, 81, 334, 104, 335, 336, 337, 338, 179, 101, 348, 383, 336, 603, 726, 783, 789, 862, 1109, 96])) {
                $cmt = 0.3;
            } elseif ($originId == 26) {
                $cmt = 0.35;
            } else {
                $cmt = 0.15;
            }
            return floor($amount - $feeAmount - $amount * $cmt);
        }
        if ($uid == 261 && $originId == 95) {
            $share = 1; //生财系列课方得教育分成
        }
        $payoff = $amount * (1 - $fee) * $share;
        return floor($payoff);
    }

    public function settlement($hour)
    {
        $time = strToDate($hour, 'Y-m-d H');
        $where = [
            'tms_update between ? and ?' => ["$time:00:00", "$time:59:59"],
            'i_status' => dataOrder::STATUS_FIRM
        ];
        $rows = dataOrder::sole($this->platform)->fetchAll($where, '*');
        $tally = 0;
        foreach ($rows as $row) {
            if ($this->confirm2($row)) { // 优先使用新结算函数
                $tally++;
            } else {
                $tally += (bool)$this->confirm($row);
            }
        }
        return [
            'count' => count($rows),
            'tally' => $tally,
        ];
    }

    public function confirm2($row)
    {
        $where = [
            'order_id' => $row['id'],
            'order_status' => dataOrder::STATUS_FIRM
        ];
        $items = dataPayoff::sole($this->platform)->fetchAll($where, '*');
        foreach ($items as $item) {
            if ($item['uid']==0) {
                continue;
            }
            $this->cash(
                $item['uid'],
                $item['i_item'],
                $item['amount'],
                ['order_sn' => $row['sn']],
                servMoney::ITEM_MAP[$item['i_item']].':'.$row['sn']
            );
        }
        return dataPayoff::sole($this->platform)->update([
            'order_status' => dataOrder::STATUS_DONE
        ], $where)->rowCount();
    }

    public function confirm($row)
    {
        $tuid = dataLesson::sole($this->platform)->fetchOne(['id' => $row['lesson_id']], 'tuid', 0);
        $extra = json_decode($row['extra'], true);
        if ($extra['promote'] ?? 0) {
            $info = servPromote::sole($this->platform)->info($extra['promote']);
            $this->cash(
                $info['uid'],
                dataMoney::ITEM_COMMISSION,
                $info['commission'],
                ['order_sn' => $row['sn'], 'promote' => $extra['promote']],
                "commission:$row[sn]"
            );
        }
        if ($extra['series'] ?? null) { //series
            $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $extra['series']], ['uid', 'scheme']);
            if ($series) {
                $suid = $series['uid'];
                $payoff[$suid] = round($row['payoff_amount'] * $series['scheme']['share'] / 100);
                $payoff[$tuid] = ($payoff[$tuid] ?? 0) + $row['payoff_amount'] - $payoff[$suid];
                //系列订单佣金分成
                $unionOrder = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $row['pay_sn']], ['order_amount', 'extra']);
                if ($unionOrder['extra']['promote']) {
                    $info = servPromote::sole($this->platform)->info($unionOrder['extra']['promote']);
                    $cost = $unionOrder['extra']['cost'];
//                $_commission = $row['order_amount'] / $unionOrder['order_amount'] * $info['commission'] * $cost['prime'] / $cost['total'];
                    $_commission = floor($info['commission'] * $extra['price'] / $cost['total']);
                    $this->cash(
                        $info['uid'],
                        dataMoney::ITEM_COMMISSION,
                        intval($_commission),
                        ['order_sn' => $row['sn'], 'promote' => $unionOrder['extra']['promote']],
                        "commission:$row[sn]"
                    );
                }
            }
        } else {
            $payoff[$tuid] = $row['payoff_amount'];
        }
        $var = 0;
        foreach ($payoff as $uid => $amount) {
            $var += (bool)$this->cash(
                $uid,
                dataMoney::ITEM_LESSON_INCOME,
                $amount,
                ['order_sn' => $row['sn']],
                "income:$row[sn]");
        }
        return $var;
    }

    public function calcExpect($order, &$cm = [])
    {
        $data = [];
        $tuid = dataLesson::sole($this->platform)->fetchOne(['id' => $order['lesson_id']], 'tuid', 0);
        $extra = is_array($order['extra']) ? $order['extra'] : json_decode($order['extra'], true);
        $order['payoff_amount'] = servPayoff::sole($this->platform)->orderPayoff($order);
        if ($extra['promote'] ?? 0) {
            $info = servPromote::sole($this->platform)->info($extra['promote']);
            $data[$info['uid']] = ($data[$info['uid']] ?? 0) + $info['commission'];
            $cm[$info['uid']] = $info['commission'];
        }
        if ( substr($extra['series'] ?? '', 0, 1) === data::SN_SERIES) { //series
            $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $extra['series']], ['uid', 'scheme']);
            $suid = $series['uid'];
            $share = round($order['payoff_amount'] * $series['scheme']['share'] / 100);
            $data[$suid] = ($data[$suid] ?? 0) + $share;
            $data[$tuid] = ($data[$tuid] ?? 0) + $order['payoff_amount'] - $data[$suid];
            //系列订单佣金分成
            $unionOrder = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $order['pay_sn']], ['order_amount', 'extra']);
            if ($unionOrder['extra']['promote'] ?? null) {
                $info = servPromote::sole($this->platform)->info($unionOrder['extra']['promote']);
                $cost = $unionOrder['extra']['cost'];
                $_commission = floor($info['commission'] * $extra['price'] / $cost['total']);
                $data[$info['uid']] = ($data[$info['uid']] ?? 0) + $_commission;
                $cm[$info['uid']] = $_commission;
            }
        } else {
            $data[$tuid] = ($data[$tuid] ?? 0) + $order['payoff_amount'];
        }
        // 过滤空值
        return array_filter($data, function($v) {
            return $v && !is_nan($v);
        });
    }

    public function calcCommission($row)
    {
        if ($row['extra']['promote'] ?? 0) {
            $info = servPromote::sole($this->platform)->info($row['extra']['promote']);
            return $info['commission'];
        }

        if ($row['extra']['series'] ?? 0) {
            $unionOrder = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $row['pay_sn']], ['order_amount', 'extra']);
            $info = servPromote::sole($this->platform)->info($unionOrder['extra']['promote']);
            $cost = $unionOrder['extra']['cost'];
//            $commission = $row['order_amount'] / $unionOrder['order_amount'] * $info['commission'] * $cost['prime'] / $cost['total'];
            $commission = floor($info['commission'] * $row['extra']['price'] / $cost['total']);
            return $commission;
        }

        return 0;
    }

    public function refund($row)
    {
        $payoff = dataPayoff::sole($this->platform)->fetchAll(['order_id' => $row['id']], '*');

        if ($payoff) { // 在分成表中有记录
            foreach ($payoff as $item) {
                if ($item['uid'] && $item['order_status'] == dataOrder::STATUS_DONE) { // 资金已结算
                    $this->cash(
                        $item['uid'],
                        dataMoney::ITEM_LESSON_REFUND,
                        -$item['amount'],
                        ['order_sn' => $row['sn']],
                        "refund:$row[sn]"
                    );
                }
            }
            // 更改分成表中对应订单状态
            dataPayoff::sole($this->platform)->update(['order_status'=>dataOrder::STATUS_REFUND], ['order_id'=>$row['id']])->rowCount();
            return true;
        }

        // 若payoff表中无记录，则按旧方式执行退款


        if ($row['i_status'] != dataOrder::STATUS_FIRM) {
            return false;
        }
        $tuid = dataLesson::sole($this->platform)->fetchOne(['id' => $row['lesson_id']], 'tuid', 0);
        //退款处理，先确认有结算
        $this->confirm($row);
        $extra = json_decode($row['extra'], true);
        if ($extra['promote']) {
            $info = servPromote::sole($this->platform)->info($extra['promote']);
            $this->cash(
                $info['uid'],
                dataMoney::ITEM_DEDUCT,
                -$info['commission'],
                ['order_sn' => $row['sn'], 'promote' => $extra['promote']],
                "deduct:$row[sn]"
            );
        }

        return $this->cash(
            $tuid,
            dataMoney::ITEM_LESSON_REFUND,
            -$row['payoff_amount'],
            ['order_sn' => $row['sn']],
            "refund:$row[sn]");
    }

    public function drawcash($uid, $amount)
    {
        $result = $this->createTransferData($uid, $amount);
        if ($result->error == 0) {
            servMpMsg::sole($this->platform)->sendEnchashmentNotice($uid, $amount / 100);
            $args = [
                'partner_trade_no' => $result->data['partner_trade_no'],
                'payment_no' => $result->data['payment_no']
            ];
            $this->cash($uid, dataMoney::ITEM_DRAWCASH, -$amount, $args, time());
        } elseif ($result->error == 2) {
            $args = [
                'partner_trade_no' => $result->data['partner_trade_no'],
                'payment_no' => $result->data['payment_no'],
                'message' => $result->message
            ];
            $this->cash($uid, dataMoney::ITEM_DRAWCASH, -$amount, $args, time());
        }
        return $result;
    }

    /**
     *
     * ◆ 给同一个实名用户付款，单笔单日限额2W/2W
     * ◆ 不支持给非实名用户打款
     * ◆ 一个商户同一日付款总额限额100W
     * ◆ 单笔最小金额默认为1元
     * ◆ 每个用户每天最多可付款10次，可以在商户平台--API安全进行设置
     * ◆ 给同一个用户付款时间间隔不得低于15秒
     * @param $uid
     * @param int $amount 单位分
     * @param $date
     * @return unitResult
     */
    public function checkDrawcash($uid, $amount, $date)
    {
        $result = unitResult::inst();

        if ($amount < 100) {
            return $result->err("提现金额不能小于￥1");
        }

        $remain = dataUserKeep::sole($this->platform)->getPayoff($uid)['remain'];
        if ($amount > $remain) {
            return $result->err("超过账户可提现金额");
        }

        $res = dataMoney::sole($this->platform)->fetchOne([
            'uid' => $uid,
            'i_item' => dataMoney::ITEM_DRAWCASH,
            'tms like ?' => [strToDate($date, 'Y-m-d %')]
        ], 'count(*) as cnt, sum(amount) as sum');

        if ($res['cnt'] >= 10) {
            return $result->err("超过当日提现次数");
        }

        if ($res['sum'] >= 2000000) {
            return $result->err("超过当日可提现额度");
        }

        //todo 增加单日100W总提现额度限制

        return $result->ok();
    }

    protected function createTransferData($uid, $amount)
    {
        $orderSn = uniqid(data::SN_DRAWCASH);
        $openid = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $result = unitResult::inst();
        if ($openid) {
            $Pay = Payinit::weixin('weixin');
            $Pay->_params = [
                'mch_appid' => $Pay->appId, //APP ID
                'mchid' => $Pay->mchId, //商户号
                'desc' => '用户提现',   //企业付款描述信息
                'partner_trade_no' => $orderSn, //商户订单号
                'amount' => $amount, //总金额，单位分
                'check_name' => 'NO_CHECK', //校验用户姓名选项  NO_CHECK：不校验真实姓名  FORCE_CHECK：强校验真实姓名
//            're_user_name' => 'xxx', //收款用户姓名
                'openid' => $openid, //用户标识
            ];
            $try = 5;
            do {
                $res = $Pay->createTransferData();
                if ($res['return_code'] == 'SUCCESS') {
                    if ($res['result_code'] == 'SUCCESS') {
                        return $result->ok($res);
                    } else {
                        return $result->err($res['return_msg']);
                    }
                } elseif ($res['ERR_CODE'] == 'SYSTEMERROR') {
                    sleep(3);
                } else {
                    return $result->err($res['return_msg']);
                }
                // ERR_CODE == SYSTEMERROR 时，重试
            } while (--$try && $result->error);
            return $result->set(2, "busy", $res); //阻塞
        } else {
            return $result->err("illegal openid");
        }
    }


    public function createPayBankData($uid = null, $encBankNo, $encTrueName, $bankCode, $amount)
    {
        $amount *= 100;
        $orderSn = uniqid(data::SN_DRAWCASH);
        $args = [
            'partner_trade_no' => $orderSn,
            'enc_bank_no' => $encBankNo, //收款方银行卡号
            'enc_true_name' => $encTrueName, //收款方用户名
            'bank_code' => $bankCode, //银行卡所在开户行编号
        ];
        $data = [
            'uid' => $uid,
            'i_item' => dataMoney::ITEM_DRAWCASH,
            'amount' => $amount,
            'args' => json_encode($args)
        ];
        dataMoney::sole($this->platform)->insert($data);
        $result = unitResult::inst();
        $Pay = Payinit::weixin('weixin');
        $Pay->_params = [
            'mch_id' => $Pay->mchId, //商户号
            'desc' => '银行卡转账',   //描述信息
            'partner_trade_no' => $orderSn, //商户订单号
            'enc_bank_no' => $encBankNo, //收款方银行卡号
            'enc_true_name' => $encTrueName, //收款方用户名
            'bank_code' => $bankCode, //银行卡所在开户行编号
            'amount' => $amount, //总金额，单位分
        ];
        $try = 5;
        do {
            $res = $Pay->createPayBankData();
            if ($res['return_code'] == 'SUCCESS') {
                if ($res['result_code'] == 'SUCCESS') {
                    return $result->ok($res);
                } else {
                    return $result->err($res['return_msg']);
                }
            } elseif ($res['ERR_CODE'] == 'SYSTEMERROR') {
                sleep(3);
            } else {
                return $result->err($res['return_msg']);
            }
            // ERR_CODE == SYSTEMERROR 时，重试
        } while (--$try && $result->error);

        return $result->err("error");

    }

    public function clearingPromote($uid)
    {
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        $oid = servOrigin::sole($this->platform)->key2id("promote-$usn");
        $res = dataOrder::sole($this->platform)->fetchAll([
            'origin_id' => $oid,
            'i_status' => dataOrder::STATUS_PAID
        ], '*'
        );
        $commission = 0;
        $srvPayoff = servPayoff::sole($this->platform);
        foreach ($res as $row) {
            $row['extra'] = json_decode($row['extra'], true);
            $commission += $srvPayoff->calcCommission($row);
        }
        return $commission;
    }

    public function clearingPayoff($uid)
    {
        $lessonIds = dataLesson::sole($this->platform)->fetchAll([
            'tuid' => $uid,
            'i_step>0',
            'i_form>0',
        ], 'id', null, 0);
        $ids = Mysql::makeData($lessonIds, '?', ',');
        $res = dataOrder::sole($this->platform)->fetchAll([
            'uid' => $uid,
            "lesson_id in ($ids[clause])" => $ids['params']
        ], '*');
        $payoff = 0;
        $srvPayoff = servPayoff::sole($this->platform);
        foreach ($res as $row) {
            $payoff += $srvPayoff->calcPayoff($uid, $row['order_amount'], $row['origin_id']);
        }
        return $payoff;
    }

    /**
     * 固定订单分成金额
     * @param $order
     * @return array
     */
    public function fixPayoff($order)
    {
        $res = $this->calculate($order);
        $data = [];
        foreach ($res as $row) {
            $key = "$row[uid]|$row[i_item]";
            $row['order_id'] = $order['id'];
            $row['order_status'] = dataOrder::STATUS_PAID;
            if (isset($data[$key])) {
                $data[$key]['amount'] += $row['amount'];
            } else {
                $data[$key] =  $row;
            }
        }
        foreach ($data as $key => &$val) {
            $val['amount'] = round($val['amount']); // 对汇总结果做四舍五入
            if (!$val['amount']) { // 如果四舍五入结果为零，摘除
                unset($data[$key]);
            }
        }
        if ($data) {
            dataPayoff::sole($this->platform)->insertBatch(
                ['uid', 'i_item', 'amount', 'order_id', 'order_status'],
                array_map('array_values', $data),
                ['order_status', 'amount']);
        }
        return $data;
    }

    public function fixPayoff_($order)
    {
        $res = $this->calculate($order);
        $data = [];
        foreach ($res as $row) {
            $key = "$row[uid]|$row[i_item]";
            $row['order_id'] = $order['id'];
            $row['order_status'] = dataOrder::STATUS_PAID;
            if (isset($data[$key])) {
                $data[$key]['amount'] += $row['amount'];
            } else {
                $data[$key] =  $row;
            }
        }
        foreach ($data as $key => &$val) {
            $val['amount'] = round($val['amount']); // 对汇总结果做四舍五入
            if (!$val['amount']) { // 如果四舍五入结果为零，摘除
                unset($data[$key]);
            }
        }
        if ($data) {
            dataPayoff_::sole($this->platform)->insertBatch(
                ['uid', 'i_item', 'amount', 'order_id', 'order_status'],
                array_map('array_values', $data),
                ['order_status', 'amount']);
        }
        return $data;
    }

    /**
     * 计算订单的分成
     * @param $order
     * @return array
     */
    public function calculate($order)
    {
        $payoff = [];
        $lesson = dataLesson::sole($this->platform)->inquireOne(['id' => $order['lesson_id']], ['id', 'tuid', 'category', 'extra']);

        if (isset($order['extra']['series'])) {
            $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $order['extra']['series']], ['uid', 'scheme', 'extra']);
        } else {
            $series = null;
        }
        $market = $this->getMarketConfig($order, $lesson, $series);
        $tuid = $lesson['tuid'];
        $suid = $series['uid'] ?? null;
        $amount = $_amount = $order['order_amount'];
        if (in_array($market['promote']['i_type'] ?? null, [
            dataPromote::TYPE_VOUCHER,
            dataPromote::TYPE_HAGGLE,
            dataPromote::TYPE_REWARD
        ]) && $tuid != $market['promote']['uid']) { // 平台承担优惠成本时，计算基数加上折扣部分
            $amount += ($market['promote']['discount'] ?? 0);
        }
        if (!$amount) { // 无金额不分成
            return $payoff;
        }

        // 技术服务费1%
        if ($techService = round($amount*0.01)) {
            $payoff[] = [
                'uid' => 0, // uid=0表示平台
                'i_item' => dataMoney::ITEM_SERVICE_FEE,
                'amount' => $techService
            ];
            $_amount -= $techService;
        }


        // 优惠促销
        if ($market['promote']) {
            $_commission = min($_amount, $market['promote']['commission']); // 限制在最大剩余金额
            $payoff[] = [
                'uid' => $market['promote']['uid'],
                'i_item' => dataMoney::ITEM_COMMISSION,
                'amount' => $_commission
            ];
            $_amount -= $_commission;
        }

        // 渠道分销
        $origin = servOrigin::sole($this->platform)->chop(['id' => $order['origin_id']], 2);
        if ($from = $origin[1] ?? null) {
            $fromUsn = $from['key'];
            $fromUid = servUser::sole($this->platform)->usn2uid($fromUsn);
        } else {
            $fromUid = $fromUsn = null;
        }

        if ($fromUid && ($fromUid==$tuid || $fromUid==$suid)) { // 讲师自己的渠道
            $_payoff = $_amount; // 讲师保留的分成
        } else if (isset($market['sharing'][$fromUsn])) { // 讲师配置的分销渠道
            $_sharing = min($_amount, $amount*$market['sharing'][$fromUsn]);
            $payoff[] = [
                'uid' => $fromUid,
                'i_item' => dataMoney::ITEM_COMMISSION,
                'amount' => $_sharing
            ];
            // 扣除渠道佣金后归讲师所有
            $_amount -= $_sharing;
            $_payoff = $_amount;
        } else if (isset($market['sharing']['_'])) { // 开启了平台推广, sharing._为开关
            if (isset($market['selling'][$fromUsn])) { // 有平台配置的渠分销渠道
                $_selling = min($_amount, $amount*$market['selling'][$fromUsn]);
                $payoff[] = [
                    'uid' => $fromUid,
                    'i_item' => dataMoney::ITEM_COMMISSION,
                    'amount' => $_selling
                ];
                $_amount -= $_selling;
            } elseif (isset($market['selling']['*'])) { // 通用分销比例
                $_selling = min($_amount, $amount*$market['selling']['*']);
                $payoff[] = [
                    'uid' => $fromUid,
                    'i_item' => dataMoney::ITEM_COMMISSION,
                    'amount' => $_selling
                ];
                $_amount -= $_selling;
            }
            // selling._为讲师分成金额,默认为扣除技术服务费后的50%
            $_payoff = floor(isset($market['selling']['_']) ? $amount*$market['selling']['_'] : ($amount-$techService)*0.5);
            $_amount -= $_payoff;
            // 平台分得剩余的部分
            $payoff[] = [
                'uid' => 0,
                'i_item' => dataMoney::ITEM_LESSON_INCOME,
                'amount' => $_amount
            ];
        } else {
            $_payoff = min($_amount, floor(($amount-$techService) * 0.5));
            $_amount -= $_payoff;
            $payoff[] = [
                'uid' => 0,
                'i_item' => dataMoney::ITEM_LESSON_INCOME,
                'amount' => $_amount
            ];
        }
        if ($series) { // 系列课创建者和讲师之间的分成
            $_sPayoff = ($_payoff * $series['scheme']['share']/100);
            $_tPayoff = $_payoff - $_sPayoff;
            $payoff[] = [
                'uid' => $suid,
                'i_item' => dataMoney::ITEM_LESSON_INCOME,
                'amount' => $_sPayoff,
            ];
            $payoff[] = [
                'uid' => $tuid,
                'i_item' => dataMoney::ITEM_LESSON_INCOME,
                'amount' => $_tPayoff
            ];
        } else {
            $payoff[] = [
                'uid' => $tuid,
                'i_item' => dataMoney::ITEM_LESSON_INCOME,
                'amount' => $_payoff
            ];
        }
        return $payoff;
    }

    /**
     * 获取分成配置比例，单课优先于系列课
     * @param $order
     * @param $lesson
     * @param $series
     * @return array
     */
    public function getMarketConfig($order, $lesson, $series)
    {
        $promote = $sharing = $selling = [];
        if (isset($order['extra']['promote'])) { // 直接优惠券
            $promote = servPromote::sole($this->platform)->info($order['extra']['promote']);
        }
        if (isset($order['extra']['series'])) { // 系列课优惠券按价格比例分配
            $unionOrder = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $order['pay_sn']], ['order_amount', 'extra']);
            $ratio = $order['extra']['price'] / $unionOrder['extra']['cost']['total']; // 占比
            if (isset($unionOrder['extra']['promote'])) {
                $promote = servPromote::sole($this->platform)->info($unionOrder['extra']['promote']);
                $promote['commission'] = floor($promote['commission'] * $ratio);
                $promote['discount'] = floor($promote['discount'] * $ratio);
            }
            foreach ($series['extra']['conf']['sharing'] ?? [] as $key => $val) {
                $sharing[$key] = floor($val * $ratio);
            }
            foreach ($series['extra']['conf']['selling'] ?? [] as $key => $val) {
                $selling[$key] = floor($val * $ratio);
            }
        }
        if (isset($lesson['extra']['conf']['sharing'])) {
            $sharing = array_merge($sharing, $lesson['extra']['conf']['sharing']);
        }
        if (isset($lesson['extra']['conf']['selling'])) {
            $selling = array_merge($selling, $lesson['extra']['conf']['selling']);
        }
        return [
            'promote' => $promote, // 通过优惠券固定下来的金额
            'sharing' => $sharing, // 讲师配置的分销比例
            'selling' => $selling, // 平台配置的分销比例
        ];
    }

    protected function cash($uid, $item, $amount, array $args = [], $sign = '')
    {
        $dataMoney = dataMoney::sole($this->platform);
        $preId = $dataMoney->fetchOne([
            'uid' => $uid,
            'i_item' => $item,
            'sign' => crc32($sign)
        ], 'id', 0);
        if ($preId || !$amount) {
            return false;
        } else {
            return servMoney::sole($this->platform)->change(
                $item,
                $uid,
                intval($amount),
                $args,
                $sign
            );
        }
    }

}