<?php


namespace _;


use Core\unitInstance;

class servOrder extends serv_
{
    use unitInstance;

    const TYPE_MAP = [
        dataOrder::TYPE_ENROLL => 'enroll',
        dataOrder::TYPE_SERIES => 'series',
        dataOrder::TYPE_ADMIRE => 'admire',
    ];

    const STATUS_MAP = [
        dataOrder::STATUS_BOOK => 'book',
        dataOrder::STATUS_PAID => 'paid',
        dataOrder::STATUS_FIRM => 'firm',
        dataOrder::STATUS_DONE => 'done',
        dataOrder::STATUS_REFUND => 'refund'
    ];

    const PAY_WAY_MAP = [
        0 => null,
        dataOrder::PAY_WAY_WEIXIN => 'weixin',
        dataOrder::PAY_WAY_UNION => 'union',
        dataOrder::PAY_WAY_WXA => 'wxa',
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($uid, $lessonId, $amount, $originId = 0, $extra = [], $iType = dataOrder::TYPE_ENROLL)
    {
        $order = dataOrder::sole($this->platform)->append($uid, $lessonId, $originId, $amount, $iType, $extra);
        return $order['sn'];
    }

    /**
     * @param $orderSn
     * @return bool
     */
    public function confirm($orderSn)
    {
        $order = dataOrder::sole($this->platform)->fetchOne(
            ['sn' => $orderSn],
            ['id', 'uid', 'lesson_id', 'i_status', 'order_amount', 'paid_amount', 'payoff_amount', 'pay_sn', 'origin_id', 'extra']
        );
        $order['extra'] = json_decode($order['extra'], true);
        if ($order['i_status'] == dataOrder::STATUS_PAID) {
            $payoff = servPayoff::sole($this->platform)->orderPayoff($order);
            $res = dataOrder::sole($this->platform)->update([
                'i_status' => dataOrder::STATUS_FIRM,
                'payoff_amount' => $payoff
            ], ['sn' => $orderSn])->rowCount();
            // 更新分成表中的状态
            dataPayoff::sole($this->platform)->update(['order_status'=>dataOrder::STATUS_FIRM], ['order_id'=> $order['id']]);
            /* 信息安全课返券
            if ($order['lesson_id'] == 436 && $order['paid_amount'] > 6900) {
                $this->rebate($order['lesson_id'], $order['uid'], $order['paid_amount']*0.3, ['order'=> $orderSn]);
            }
            */
            stats\servLesson::sole($this->platform)->varPayoff($order['lesson_id'], $order['origin_id'], $payoff);
            return boolval($res);
        } elseif ($order['i_status'] == dataOrder::STATUS_FIRM) {
            return true;
        }
        else {
            return false;
        }
    }

    public function rebate($lessonId, $uid, $amount, $args)
    {
        servMoney::sole($this->platform)->change(dataMoney::ITEM_REBATE, $uid, $amount, $args);
        $lessonSn = servLesson::sole($this->platform)->id2sn($lessonId);
        servMpMsg::sole($this->platform)->sendCashBackNotice($lessonSn, $uid, $amount/100);
    }

    public function refund($orderSn, $amount = null)
    {
    }

    /**
     * 单课订单完成支付
     * @param $orderSn
     * @param $paidAmount
     * @param array $update
     * @return bool
     */
    public function purchase($orderSn, $paidAmount, array $update = [])
    {
        $orderInfo = dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], '*');
        $orderInfo['extra'] = json_decode($orderInfo['extra'], true) ?? [];
        $extra = array_merge($update['extra'] ?? [], $orderInfo['extra']);
        $balance = dataMoney::sole($this->platform)->balance($orderInfo['uid']);
        $charge = $orderInfo['order_amount'] - $paidAmount; //需要余额支付的部分
        if ($balance >= $charge) { //资金足够
            $update['paid_amount'] = $paidAmount;
            $update['i_status'] = dataOrder::STATUS_PAID;
            if ($charge != 0) {
                $var = servMoney::sole($this->platform)->change(
                    dataMoney::ITEM_PURCHASE,
                    $orderInfo['uid'],
                    -$charge,
                    ['order_id' => $orderInfo['id']],
                    time());
                $extra = array_merge($extra, ['var' => $var]);
            }
            $update['extra'] = json_encode($extra, JSON_FORCE_OBJECT);
            dataOrder::sole($this->platform)->update($update, ['sn' => $orderSn])->rowCount();
            if (!servLesson::sole($this->platform)->isEnrolled($orderInfo['lesson_id'], $orderInfo['uid'])) {
                stats\servLesson::sole($this->platform)->varEnroll($orderInfo['lesson_id'], $orderInfo['origin_id']);
                stats\servLesson::sole($this->platform)->varIncome($orderInfo['lesson_id'], $orderInfo['origin_id'], $orderInfo['order_amount']);
                // 推送课程报名通知
                servMpMsg::sole($this->platform)->sendEnrollMsg(servLesson::sole($this->platform)->id2sn($orderInfo['lesson_id']), $orderInfo['uid'], $orderInfo);
                dataLessonAccess::sole($this->platform)->append($orderInfo['lesson_id'], $orderInfo['uid'], dataLessonAccess::EVENT_ENROLL, [
                    'origin' => $orderInfo['origin_id'],
                    'ip' => \input::ip(),
                ]);
                /*
                $_expect = servPayoff::sole($this->platform)->calcExpect($orderInfo);
                foreach ($_expect as $_uid => $_val)  {
                    dataUserKeep::sole($this->platform)->varAttr($_uid, dataUserKeep::ITEM_MONEY, '$.expect', $_val);
                }
                */
            }
            if (isset($extra['promote'])) { //处理优惠报名
                servPromote::sole($this->platform)->afterPurchase($extra['promote']);
                // 向分享人发起人推送购买通知
//                servMpMsg::sole($this->platform)->sendPromoteMsg($extra['promote'], $orderInfo['uid']);

            }
            // 在完成购买时，将分成金额固定下来
            $payoff = servPayoff::sole($this->platform)->fixPayoff($orderInfo);
            $lessonSn = servLesson::sole($this->platform)->id2sn($orderInfo['lesson_id']);
            foreach ($payoff as $item) {
                // 推送佣金通知（分享奖励+渠道分销）
                if ($item['uid'] && $item['i_item'] == dataMoney::ITEM_LESSON_INCOME) {
                    servMpMsg::sole($this->platform)->sendCommissionMsg($item['uid'], $orderInfo['uid'], $lessonSn, $item['amount'], '开设');
                }
                if ($item['i_item'] == dataMoney::ITEM_COMMISSION) {
                    servMpMsg::sole($this->platform)->sendCommissionMsg($item['uid'], $orderInfo['uid'], $lessonSn, $item['amount'], '推荐');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function findPaidOrder($uid, $lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $sn = dataOrder::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'lesson_id' => $lessonId, 'i_status>0'],
            'sn',
            0);
        return $sn;
    }

    public function findPaidAmountOrder($uid, $lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $paidAmount = dataOrder::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'lesson_id' => $lessonId, 'i_status' => dataOrder::STATUS_PAID],
            'paid_amount',
            0);
        return $paidAmount;
    }

    public function findOrderAmountOrder($uid, $lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $orderAmount = dataOrder::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'lesson_id' => $lessonId, 'i_status' => dataOrder::STATUS_PAID],
            'order_amount',
            0);
        return $orderAmount;
    }

    public function findLastRefundOrder($uid, $lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $order = dataOrder::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'lesson_id' => $lessonId, 'i_status' => dataOrder::STATUS_REFUND],
            '*',
            null,
            'order by id desc limit 1'
        );
        return $order;
    }

    public function findOrderByLessonId($uid, $lessonId)
    {
        $order = dataOrder::sole($this->platform)->getInfo($uid, $lessonId);
        if(!isset($order['id'])) {
            return false;
        }
        $order['order_amount'] /= 100;
        $order['i_status'] = self::STATUS_MAP[$order['i_status']];
        $order['i_pay_way'] = self::PAY_WAY_MAP[$order['i_pay_way']];
        $order['paid_amount'] /= 100;
        $var = $order['extra']['var'] ?? [];
        $psn = $order['extra']['promote'] ?? null;
        if ($psn) {
            $promote = servPromote::sole($this->platform)->info($psn);
            $order['promote_discount'] = $promote['discount'] / 100;
        }
        $cash = ($var['cash']??0) ? -$var['cash'] / 100 : 0;
        $voucher = ($var['voucher']??0) ? -$var['voucher'] / 100 : 0;
        $var['balance'] = $cash + $voucher;
        $order['paid_balance'] = round($var['balance'],2);
        unset($order['extra']);
        return $order;

    }


    public function detail($orderSn)
    {
        $order = dataOrder::sole($this->platform)->fetchOne(
            ['sn' => $orderSn],
            ['id', 'sn', 'uid', 'lesson_id', 'i_status', 'order_amount', 'i_pay_way', 'pay_sn', 'extra', 'tms_create', 'tms_update']
        );
        $order['order_amount'] /= 100;
        $order['i_status'] = self::STATUS_MAP[$order['i_status']];
        $order['i_pay_way'] = self::PAY_WAY_MAP[$order['i_pay_way']];
        $order['extra'] = json_decode($order['extra'], true);
        $item['student'] = servUser::sole($this->platform)->uid2profile($order['uid']);
        $item['lesson'] = servLesson::sole($this->platform)->profile(servLesson::sole($this->platform)->id2sn($order['lesson_id']));
        $item['order'] = $order;
        $item['refund'] = ($order['id']??0) ? dataRefund::sole($this->platform)->fetchOne(['order_id' => $order['id']], ['sn', 'i_status', 'tms_update']) : false;
        $item['refund']['i_status'] = servRefund::STATUS_MAP[$item['refund']['i_status']] ?? null;
        return $item;
    }

    public function inquiry($order)
    {
        $res = dataOrder::sole($this->platform)->inquireOne(['sn'=>$order], '*');
        $keep = dataUserKeep::sole($this->platform)->obj($res['uid'], dataUserKeep::ITEM_MONEY);
        $charge = min($keep['balance'], $res['order_amount']);
        $surplus = $res['order_amount'] - $charge;
        $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $res['lesson_id']], ['title', 'price']);
        $data = [
            'sn' => $order,
            'title' => $lesson['title'],
            'uid' => $res['uid'],
            'order_total' => $lesson['price'],
            'order_price' => $lesson['price'],
            'order_amount' => $res['order_amount'],
            'deduct' => $lesson['price'] - $res['order_amount'],
            'charge' => $charge,
            'surplus' => $surplus,
            'status' => self::STATUS_MAP[$res['i_status']],
            'list' => [$lesson]
        ];
        return $data;
    }

    public function admired($uid, $lessonSn, int $limit)
    {
        $dao = dataOrder::sole($this->platform);
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $where = [
            'lesson_id' => $lessonId,
            'i_type' => dataOrder::TYPE_ADMIRE,
            'i_status' => dataorder::STATUS_DONE
        ];
        $list = [];
        $self = $dao->fetchOne(array_merge($where, ['uid' => $uid]), "count(*)", 0);
        if ($self) {
            $list[] = $uid;
            $limit--;
        }
        $others = $dao->fetchAll(array_merge($where, ['uid<>?' => [$uid]]), "uid, sum(paid_amount) as amount", null, 'uid', "group by uid order by amount limit $limit");
        $uids = array_merge($list, $others);
        return servUser::sole($this->platform)->uids2profile($uids);
    }


}