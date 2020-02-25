<?php

namespace Student\pay;

use Core\unitAPI;

class ctrlRefund extends ctrl_
{
    use unitAPI;

    const ERROR_PARAMETER_MISSING = ['9.2.1', 'parameter missing'];


    public function _POST_apply()
    {
    }
    /**
     * 微信退款查询 以下参数四选一
     * @param transaction_id string 微信订单号
     * @param order_sn string 内部订单号
     * @param  refund_sn string 内部退款单号
     * @param refund_id string 微信退款单号
     */
    public function _DO_query()
    {
        $transactionId = $this->apiGET('transaction_id','');
        $outTradeNo = $this->apiGET('order_sn','');
        $outRefundNo = $this->apiGET('refund_sn','');
        $refundId = $this->apiGET('refund_id','');
        $params = [];
        if ($transactionId) {
            $params['transaction_id'] = $transactionId;
        }
        if ($outTradeNo) {
            $params['out_trade_no'] = $outTradeNo;
        }
        if ($outRefundNo) {
            $params['out_refund_no'] = $outRefundNo;
        }
        if ($refundId) {
            $params['refund_id'] = $refundId;
        }
        if (empty($params)) {
            $this->apiFailure(self::ERROR_PARAMETER_MISSING);
        }
        $ret = servRefund::sole($this->platform)->query($params);
        $this->apiSuccess($ret);
    }
}