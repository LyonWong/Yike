<?php


namespace _\callback\weixin;

use _\weixin\servPay;

class ctrlPay extends ctrl_
{
    public function _DO_wxa()
    {
        $xml = file_get_contents('php://input');
        $srvPay = servPay::sole($this->platform);
        $res = $srvPay->resultNotifyWxa($xml);
        if ($res) {
            $data = [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK',
            ];
        } else {
            $data = [
                'return_code' => 'FAIL',
                'return_msg' => 'error',
            ];
        }
        \view::tpl('wxapi/return', $data);
    }

}