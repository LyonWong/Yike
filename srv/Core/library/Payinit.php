<?php

namespace Core\library;
require PATH_ROOT . '/library/Pay/Weixinpay/WeixinPay.php';

use Pay\WeixinPay\WeixinPay;

class Payinit
{


    /**微信支付
     * @param $index
     * @return WeixinPay
     * @throws \coreException
     */
    public static function weixin($index)
    {
        $inst = new WeixinPay();
        $config = \config::load('pay', $index);
        foreach ($config as $key => $val) {
            if (method_exists($inst, $key)) {
                $inst->$key(...$val);
            } else {
                $inst->$key = $val;
            }
        }
        return $inst;

    }

    /**
     * 支付宝
     * @param $index
     */
    public function ali($index)
    {

    }

}