<?php


namespace _;


use _\weixin\servPay;
use Core\unitInstance;

class servRefund extends serv_
{
    use unitInstance;

    const STATUS_MAP = [
        dataRefund::STATUS_ASKING => 'asking',
        dataRefund::STATUS_FINISH => 'finish',
    ];

    const TICKET_STATUS_MAP = [
        dataTicket::STATUS_START => 'start',
        dataTicket::STATUS_PENDING => 'pending',
        dataTicket::STATUS_AGREE => 'agree',
        dataTicket::STATUS_REJECT => 'reject',
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function apply($uid, $lessonSn, $reason)
    {
        $content = [
            'lesson_sn' => $lessonSn,
            'reason' => $reason,
        ];
        $tuid = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'tuid', 0);
        //提交和指派可以考虑合并
        $ticketId = dataTicket::sole($this->platform)->commit(dataTicket::TYPE_REFUND_APPLY, $uid, $content);
        return dataTicket::sole($this->platform)->assign($ticketId, $tuid);
    }

    public function appeal($uid, $lessonSn, $reason)
    {
        $content = [
            'lesson_sn' => $lessonSn,
            'reason' => $reason,
        ];
        return dataTicket::sole($this->platform)->commit(dataTicket::TYPE_REFUND_APPEAL, $uid, $content);
    }

    public function applyList($uid, $page, $limit, $iStatus)
    {

        if ($iStatus) {
            $where = ['_uid' => $uid, 'i_type' => dataTicket::TYPE_REFUND_APPLY, "i_status < " . dataTicket::STATUS_START];
        } else {
            $where = ['_uid' => $uid, 'i_type' => dataTicket::TYPE_REFUND_APPLY, 'i_status > ' . dataTicket::STATUS_AGREE];
        }
        $rets = dataTicket::sole($this->platform)->paging($page, $limit, $where,
            ['id', 'uid', 'content', 'i_status', 'i_type', 'remark', 'tms_create'],
            'tms_update desc'
        );
        $lists = [];
        foreach ($rets['pages'] as $ret) {
            $ret['content'] = json_decode($ret['content'], true);
            $ret['content']['applyer'] = servUser::sole($this->platform)->uid2info($ret['uid'], 'name')['name'];
            $ret['i_status'] = self::TICKET_STATUS_MAP[$ret['i_status']];
            $ret['tms_end'] = date('Y-m-d H:i:s', strtotime("+3 day", strtotime($ret['tms_create'])));
            $list['ticket'] = $ret;
            $list['lesson'] = servLesson::sole($this->platform)->profile($ret['content']['lesson_sn'], 'sn');
            $lists[] = $list;
        }
        $rets['pages'] = $lists;
        return $rets;
    }

    public function dealApply($uid, $remark = '', $operate, $ticketId, $force = false)
    {
        $info = dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], ['uid', '_uid', 'i_type', 'content']);
        if ($info && (($info['_uid'] && $info['_uid'] == $uid) || !$info['_uid'] || $force)) {
            if (!empty($remark)) {
                dataTicket::sole($this->platform)->remark($ticketId, $remark);
            }
            if ($operate == dataTicket::STATUS_AGREE) {
                $ret = \Student\pay\servRefund::sole($this->platform)->refund(json_decode($info['content'], true)['lesson_sn'], $info['uid']);
                if ($ret) {
                    dataTicket::sole($this->platform)->update(['i_status' => dataTicket::STATUS_AGREE,], ['id' => $ticketId]);
                    servMpMsg::sole($this->platform)->sendRefundMsg(json_decode($info['content'], true)['lesson_sn'], $info['uid']);
                    return true;
                } else {
                    return false;
                }
            } else {
                $res = dataTicket::sole($this->platform)->update(['i_status' => dataTicket::STATUS_REJECT,], ['id' => $ticketId])->rowCount();
                if ($info['i_type'] == dataTicket::TYPE_REFUND_APPLY) {
                    servMpMsg::sole($this->platform)->sendRefundApplyFailedMsg(json_decode($info['content'], true)['lesson_sn'], $info['uid'], $remark);
                } else {
                    servMpMsg::sole($this->platform)->sendRefundAppealFailedMsg(json_decode($info['content'], true)['lesson_sn'], $info['uid'], $remark);
                }
                return $res;
            }
        }
        return false;
    }

    /**
     * 退还过期未听课的报名费
     * @param $lessonSn
     */
    public function autoRefund($lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        //todo 这里的24小时条件需要和退款策略关联，同时考虑后报名的情况
        $uids = dataOrder::sole($this->platform)->fetchAll([
            'lesson_id' => $lessonId,
            'i_status' => dataOrder::STATUS_PAID,
            'tms_update < ?' => [strToDate('-24 hours', 'Y-m-d H:i:s')]
        ], 'uid', null, 0);
        $servMpMsg = servMpMsg::sole($this->platform);
        $dataAccess = dataLessonAccess::sole($this->platform);
        foreach ($uids as $uid) {
            $autoRefundSetting = servUser::sole($this->platform)->uid2setting($uid, dataUser::AUTO_REFUND); //判断是否设置自动退款
            if ($autoRefundSetting) {
                $res = \Student\pay\servRefund::sole($this->platform)->refund($lessonSn, $uid);
                if ($res) {
                    $servMpMsg->sendAutoRefundMsg($lessonSn, $uid);
                    $dataAccess->append($lessonId, $uid, dataLessonAccess::EVENT_RESET, ['reason' => 'auto refund']);
                }
            }

        }
    }

    public function prepareLesson($sn, $susn)
    {
        $uid = servUser::sole($this->platform)->usn2uid($susn);
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $sn], ['id', 'sn', 'title']);
        $order = dataOrder::sole($this->platform)->fetchOne(['uid' => $uid, 'lesson_id' => $lesson['id'], 'i_status=1', 'order_amount>0'], '*', null, 'order by id desc limit 1');
        $data = [];
        $_extra = json_decode($order['extra'], true);
        $data[] = [
            'order_id' => $order['id'],
            'order_sn' => $order['sn'],
            'origin_id' => $order['origin_id'],
            'order_amount' => $order['order_amount'],
            'paid_amount' => $order['paid_amount'],
            'i_pay_way' => $order['i_pay_way'],
            'pay_sn' => $order['pay_sn'],
            'money_var' => $_extra['var'] ?? [],
            'lesson_id' => $lesson['id'],
            'lesson_sn' => $lesson['sn'],
            'title' => $lesson['title'],
            'extra' => $order['extra'],
        ];
        return $data;
    }

    public function prepareSeries($sn, $susn)
    {
        $uid = servUser::sole($this->platform)->usn2uid($susn);
        $lessons = dataLesson::sole($this->platform)->fetchAll(['category' => $sn], ['id', 'sn', 'title'], 'id');
        $orders = dataOrder::sole($this->platform)->fetchByUserLessons($uid, array_keys($lessons), ['i_status=1', 'order_amount>0']);
        $data = [];
        foreach ($orders as $order) {
            $_lesson = $lessons[$order['lesson_id']];
            $_extra = json_decode($order['extra'], true);
            $data[] = [
                'order_id' => $order['id'],
                'order_sn' => $order['sn'],
                'origin_id' => $order['origin_id'],
                'order_amount' => $order['order_amount'],
                'paid_amount' => $order['paid_amount'],
                'i_pay_way' => $order['i_pay_way'],
                'pay_sn' => $order['pay_sn'],
                'money_var' => $_extra['var'] ?? [],
                'lesson_id' => $_lesson['id'],
                'lesson_sn' => $_lesson['sn'],
                'title' => $_lesson['title'],
                'extra' => $order['extra'],
            ];
        }
        return $data;
    }

    public function genRefundSn($orderId, $unionId, $uid, $amount)
    {
        $dao = dataRefund::sole($this->platform);
        if ($sn = $dao->fetchOne(['order_id' => $orderId, 'union_id' => $unionId], 'sn')) {
            return $sn;
        } else {
            return $dao->append($orderId, $unionId, $uid, $amount);
        }
    }

    /**
     * 系列课退款（未听课订单）
     * @param $seriesSn
     * @param $susn
     * @param null $reason
     * @return array
     */
    public function refundSeries($seriesSn, $susn, $reason = null)
    {
        $uid = servUser::sole($this->platform)->usn2uid($susn);
        $items = $this->prepareSeries($seriesSn, $susn);
        $parts = [];
        // 按支付订单分组
        $daoAccess = dataLessonAccess::sole($this->platform);
        foreach ($items as $item) {
            $key = substr($item['pay_sn'], 0, 2) == 'UO' ? $item['pay_sn'] : $item['order_sn'];
            $parts[$key]['i_pay_way'] = $item['i_pay_way'];
            $parts[$key]['paid_amount'] = ($parts[$key]['paid_amount'] ?? 0) + $item['paid_amount']; // 第三方支付
            $parts[$key]['money_use'] = ($parts[$key]['money_use'] ?? 0) + $item['order_amount'] - $item['paid_amount']; // 余额支付
            foreach ($item['money_var'] ?? [] as $i => $v) {
                $parts[$key]['money_var'][$i] = ($parts[$key]['money_var'][$i] ?? 0) + $v;
            }

            $daoAccess->append($item['lesson_id'], $uid, dataLessonAccess::EVENT_REFUND);
            $hasAccessed = servLesson::sole($this->platform)->hasEvent($item['lesson_sn'], $uid, dataLessonAccess::EVENT_ACCESS);
            if (!$hasAccessed) { // 未进入课堂的退款，重置
                dataLessonAccess::sole($this->platform)->append($item['lesson_id'], $uid, dataLessonAccess::EVENT_RESET);
            }
            // 将订单置为退款状态
            dataOrder::sole($this->platform)->update(['i_status' => dataOrder::STATUS_REFUND], ['id' => $item['order_id']]);
            // 将结算记录置为退款状态
            dataPayoff::sole($this->platform)->update(['order_status' => dataOrder::STATUS_REFUND], ['order_id' => $item['order_id']]);
            // 更新统计信息
            stats\servLesson::sole($this->platform)->varIncome($item['lesson_id'], $item['origin_id'], -$item['order_amount']);
            stats\servLesson::sole($this->platform)->varRefund($item['lesson_id'], $item['origin_id'], $item['order_amount']);

            // todo 取消未结算收入计算
            /*
            $_expect = servPayoff::sole($this->platform)->calcExpect($item);
            foreach ($_expect as $_uid => $_val) {
                dataUserKeep::sole($this->platform)->varAttr($_uid, dataUserKeep::ITEM_MONEY, '$.expect', -$_val);
            }
            */
            servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_REMIND, ['usn' => $susn, 'lesson_sn' => $item['lesson_sn']]);
            servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_LAPSE, ['uid' => $uid, 'lesson_id' => $item['lesson_id']]);

        }
        $srvMoney = servMoney::sole($this->platform);
        $res = [];
        foreach ($parts as $key => $val) {
            // 返还资金路线
            $res[0] = ($res[0] ?? 0) + $val['money_use'];

            if ($key == '') { // 全余额支付的部分
                $srvMoney->change(dataMoney::ITEM_RETURN, $uid, $val['money_use'], [
                    'series_sn' => $seriesSn,
                    'var' => $val['money_var']
                ]);
            } elseif (substr($key, 0, 2) == 'UO') { // 联合订单部分
                $srvMoney->change(dataMoney::ITEM_RETURN, $uid, $val['money_use'], [
                    'union_order' => $key,
                    'var' => $val['money_var']
                ]);
                if ($val['i_pay_way']) { // 第三方支付退还
                    $_order = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $key], ['id', 'i_pay_way', 'pay_sn', 'paid_amount']);
                    $refundSn = $this->genRefundSn(0, $_order['id'], $uid, $val['paid_amount']);
                    $ver = servPay::sole($this->platform)->refund($_order['i_pay_way'], $key, $refundSn, $_order['paid_amount'], $val['paid_amount'], $reason);
                    if ($ver) {
                        $res[$val['i_pay_way']] = ($res['i_pay_way'] ?? 0) + ($val['paid_amount'] ?? 0);
                    } else {
                        \output::debug('refund_error', $_order, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
                    }
                }
            } else { // 购买单课部分
                $srvMoney->change(dataMoney::ITEM_RETURN, $uid, $val['money_use'], [
                    'order_sn' => $val['order_sn'],
                    'var' => $val['money_var']
                ]);
                $_order = dataOrder::sole($this->platform)->fetchOne(['sn' => $key], ['id']);
                $refundSn = $this->genRefundSn($_order['id'], 0, $uid, $val['paid_amount']);
                $ver = servPay::sole($this->platform)->refund($val['i_pay_way'], $key, $refundSn, $val['paid_amount'], $val['paid_amount'], $reason);
                if ($ver) {
                    $res[$val['i_pay_way']] = ($res['i_pay_way'] ?? 0) + ($val['paid_amount'] ?? 0);
                } else {
                    \output::debug('refund_error', $_order, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
                }
            }
        }
        return $res;
    }

    /**
     * 单课退款
     * @param $lessonSn
     * @param $susn
     * @param null $reason
     * @return array|mixed
     */
    public function refundLesson($lessonSn, $susn, $reason = null)
    {
        $srvLesson = servLesson::sole($this->platform);
        $srvUser = servUser::sole($this->platform);
        $daoOrder = dataOrder::sole($this->platform);
        $lessonId = $srvLesson->sn2id($lessonSn);
        $uid = $srvUser->usn2uid($susn);
        $order = $daoOrder->inquireOne(['lesson_id' => $lessonId, 'uid' => $uid, 'i_status>0', 'order_amount>0'], '*');
        if (!$order) {
            return null; // 无可退款订单
        }
        $refundSn = $this->genRefundSn($order['id'], 0, $uid, $order['paid_amount']);
        if ($order['i_pay_way']) { // 外部支付退款
            switch ($order['i_type']) {
                case dataOrder::TYPE_SERIES: // 系列订单
                    $outTradeNo = $order['pay_sn'];
                    $totalFee = dataUnionOrder::sole($this->platform)->fetchOne(['sn'=>$order['pay_sn']], 'paid_amount', 0);
                    break;
                default:
                    $outTradeNo = $order['sn'];
                    $totalFee = $order['paid_amount'];
            }
            $ver = servPay::sole($this->platform)->refund( // 退还第三方支付
                $order['i_pay_way'],
                $outTradeNo,
                $refundSn,
                $totalFee,
                $order['paid_amount'],
                $reason
            );
        } else {
            $ver = true;
        }

        if (!$ver) {
            \output::debug('refund_error', $order, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
            return false; // 退款失败
        }

        // 退还余额
        servMoney::sole($this->platform)->change(
            dataMoney::ITEM_RETURN,
            $order['uid'],
            $order['order_amount'] - $order['paid_amount'],
            ['order_id' => $order['id'], 'var' => $order['extra']['var'] ?? []],
            $refundSn);

        // 扣除分成（若有）
        servPayoff::sole($this->platform)->refund($order);

        // 扣除待结算收入
        // todo 取消
        /*
        $expect = servPayoff::sole($this->platform)->calcExpect($order);
        foreach ($expect as $_uid => $_val) {
            dataUserKeep::sole($this->platform)->varAttr($_uid, dataUserKeep::ITEM_MONEY, '$.expect', -$_val);
        }
        */

        // 更新听课记录
        $daoAccess = dataLessonAccess::sole($this->platform);
        $daoAccess->append($lessonId, $uid, dataLessonAccess::EVENT_REFUND);
        $hasAccessed = servLesson::sole($this->platform)->hasEvent($lessonSn, $uid, dataLessonAccess::EVENT_ACCESS);
        if (!$hasAccessed) { // 未进入课堂的退款，重置
            dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_RESET);
        }

        dataOrder::sole($this->platform)->update(['i_status' => dataOrder::STATUS_REFUND], ['id' => $order['id']]);
        stats\servLesson::sole($this->platform)->varIncome($lessonId, $order['id'], -$order['order_amount']);
        stats\servLesson::sole($this->platform)->varRefund($lessonId, $order['id'], $order['order_amount']);

        servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_REMIND, ['usn' => $susn, 'lesson_sn' => $lessonSn]);
        servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_LAPSE, ['uid' => $uid, 'lesson_id' => $lessonId]);

        $res = [];
        if ($order['order_amount'] != $order['paid_amount']) {
            $res[''] = $order['order_amount'] - $order['paid_amount'];
        }
        if ($ver) {
            $res[$order['i_pay_way']] = $order['paid_amount'];
        }
        return $res;
    }
}