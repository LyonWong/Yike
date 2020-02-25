<?php

namespace Student\pay;


use _\dataLesson;
use _\dataMoney;
use _\dataUnionOrder;
use _\dataUserKeep;
use _\servPayoff;
use _\servTrigger;
use _\stats\servLesson;
use _\weixin\servPay;
use Core\unitInstance;
use Student\dataOrder;
use Student\dataRefund;
use Student\serv_;
use Core\library\Payinit;
use _\dataLessonAccess;
use Student\servMoney;
use Student\servUser;

class servRefund extends serv_
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

    public function __construct($platform = null)
    {
        parent::__construct($platform);
    }

    public function refund($lessonSn, $uid)
    {
        $lessonId = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id'], 'id');
        $order = dataOrder::sole($this->platform)->getInfo($uid, $lessonId);
        if ($order && $order['i_status'] > 0 && $order['order_amount'] > 0) {
            $refundSn = $this->refundOrder($order['id'], $uid, $order['order_amount']);
            switch ($order['i_pay_way']) {
                case 0;
                    $ret = servMoney::sole($this->platform)->change(
                        dataMoney::ITEM_RETURN,
                        $order['uid'],
                        $order['order_amount'],
                        ['order_id' => $order['id'], 'var' => $order['extra']['var'] ?? []],
                        $refundSn);
                    break;
                case dataOrder::PAY_WAY_WEIXIN;
                    $Pay = Payinit::weixin('weixin');
                    switch ($order['i_type']) {
                        case dataOrder::TYPE_SERIES:
                            $outTradeNo = $order['pay_sn'];
                            $totalFee = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $order['pay_sn']], 'paid_amount', 0);
                            break;
                        default:
                            $outTradeNo = $order['sn'];
                            $totalFee = $order['paid_amount'];
                            break;
                    }
                    $Pay->_params = [
                        'appid' => $Pay->appId, //APP ID
                        'mch_id' => $Pay->mchId, //商户号
                        'out_trade_no' => $outTradeNo,//内部订单号
                        'out_refund_no' => $refundSn, //内部退款单号
                        'total_fee' => $totalFee,
                        'refund_fee' => $order['paid_amount'],
                        'op_user_id' => $Pay->mchId,
                    ];
                    $ret = $Pay->createRefundData();
                    if ($ret && ($order['order_amount'] != $order['paid_amount'])) {
                        $ret = servMoney::sole($this->platform)->change(
                            dataMoney::ITEM_RETURN,
                            $order['uid'],
                            $order['order_amount'] - $order['paid_amount'],
                            ['order_id' => $order['id'], 'var' => $order['extra']['var'] ?? []],
                            $refundSn
                        );
                    }
                    break;
                case dataOrder::PAY_WAY_WXA:
                    $srvPay = servPay::sole($this->platform);
                    switch ($order['i_type']) {
                        case dataOrder::TYPE_SERIES:
                            $outTradeNo = $order['pay_sn'];
                            $totalFee = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $order['pay_sn']], 'paid_amount', 0);
                            break;
                        default:
                            $outTradeNo = $order['sn'];
                            $totalFee = $order['paid_amount'];
                            break;
                    }
                    $res = $srvPay->refundWxa($outTradeNo, $refundSn, $totalFee, $order['paid_amount']);
                    if ($res && ($order['order_amount'] != $order['paid_amount'])) {
                        $ret = servMoney::sole($this->platform)->change(
                            dataMoney::ITEM_RETURN,
                            $order['uid'],
                            $order['order_amount'] - $order['paid_amount'],
                            ['order_id' => $order['id'], 'var' => $order['extra']['var'] ?? []],
                            $refundSn
                        );
                    }
                    break;
                default:
                    $ret = false;
            }
            if ($ret !== false) {
                dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_REFUND);
                dataOrder::sole($this->platform)->update(['i_status' => dataOrder::STATUS_REFUND], ['sn' => $order['sn']]);
                servLesson::sole($this->platform)->varIncome($lessonId, $order['origin_id'], -$order['order_amount']);
                servLesson::sole($this->platform)->varRefund($lessonId, $order['origin_id'], $order['order_amount']);
                servLesson::sole($this->platform)->varPayoff($lessonId, $order['origin_id'], -$order['payoff_amount']);
                $_order = $order;
                $_order['extra'] = json_encode($_order['extra']);
                servPayoff::sole($this->platform)->refund($_order);
                $_expect = servPayoff::sole($this->platform)->calcExpect($order);
                foreach ($_expect as $_uid => $_val) {
                    dataUserKeep::sole($this->platform)->varAttr($_uid, dataUserKeep::ITEM_MONEY, '$.expect', -$_val);
                }

                $usn = servUser::sole($this->platform)->uid2usn($uid);
                servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_REMIND, ['usn' => $usn, 'lesson_sn' => $lessonSn]);
                servTrigger::sole($this->platform)->cancel(servTrigger::TAG_REFUND_LAPSE, ['uid' => $uid, 'lesson_id' => $lessonId]);
                return dataRefund::sole($this->platform)->update(
                    [
                        'lesson_id' => $lessonId,
                        'i_status' => dataRefund::STATUS_FINISH,
                        'tms_finish' => date('Y-m-d H:i:s', time())
                    ],
                    ['sn' => $refundSn])->rowCount();
            }
            return false;
        }
        return false;
    }

    public function refundOrder($orderId, $uid, $amount)
    {
        $refundSn = dataRefund::sole($this->platform)->fetchOne(['order_id' => $orderId], 'sn', 'sn');
        if ($refundSn) {
            return $refundSn;
        }
        return dataRefund::sole($this->platform)->append($orderId, 0, $uid, $amount);
    }

    public function query($params)
    {
        $Pay = Payinit::weixin('weixin');
        $Pay->_params = [
            'appid' => $Pay->appId, //APP ID
            'mch_id' => $Pay->mchId, //商户号
        ];
        if (isset($params['transaction_id'])) {
            $Pay->_params['transaction_id'] = $params['transaction_id'];
        }
        if (isset($params['out_trade_no'])) {
            $Pay->_params['out_trade_no'] = $params['out_trade_no'];
        }
        if (isset($params['out_refund_no'])) {
            $Pay->_params['out_refund_no'] = $params['out_refund_no'];
        }
        if (isset($params['refund_id'])) {
            $Pay->_params['refund_id'] = $params['refund_id'];
        }
        return $Pay->refundQuery();
    }
}