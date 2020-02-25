<?php

namespace Student\pay;


use _\servUnionOrder;
use Core\library\QRcode;
use Core\unitAPI;

class ctrlOrder extends ctrl_
{
    use unitAPI;


    public function _POST_confirm()
    {
    }

    /**
     * 微信订单查询
     * @param order_sn string 内部订单号
     */
    public function _DO_query()
    {
        $orderSn = $this->apiGET('order_sn');
        $ret = servOrder::sole($this->platform)->query($orderSn);
        $this->apiSuccess($ret);
    }

    public function _DO_inquiry()
    {
        $order = $this->apiGET('order');
        $data = servOrder::sole($this->platform)->inquiry($order);
        $this->apiSuccess($data);

    }
    public function _GET_codeUrl()
    {
        $url = $this->apiGET('url');
        $url = base64_decode($url);
        header("Content-type: image/png");
        QRcode::png($url, false, QR_ECLEVEL_H, 7);
    }
}