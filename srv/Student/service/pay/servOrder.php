<?php

namespace Student\pay;

use _\servUnionOrder;
use Student\dataLesson;
use Student\dataOrder;
use Student\serv_;
use Core\unitInstance;
use Student\servUser;
use Core\library\Payinit;

class servOrder extends serv_
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

    public function createJsPayData($uid, $orderSn, $body, $amount=null)
    {
        $info = servUser::sole($this->platform)->uid2info($uid, 'openid');
        $orderAmount = $amount ?: dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], 'order_amount', 0);
        $openid = $info['openid'];
        if ($orderAmount && $openid) {
            $Pay = Payinit::weixin('weixin');
            $Pay->_params = [
                'appid' => $Pay->appId, //APP ID
                'mch_id' => $Pay->mchId, //商户号
                'body' => $body, //商品描述
                'attach' => intval($uid),   //附加数据
                'out_trade_no' => $orderSn, //内部订单号
                'total_fee' => $orderAmount, //总金额，单位分
                'trade_type' => 'JSAPI', //交易类型
                'openid' => $openid, //用户标识
                'notify_url' => \config::load('pay', 'weixin', 'notifyUrl'),
            ];
            return $Pay->createJsPayData();
        } else {
            return false;
        }
    }
    public function createNativePayData($uid, $orderSn, $body, $amount=null)
    {
        $info = servUser::sole($this->platform)->uid2info($uid, 'openid');
        $orderAmount = $amount ?: dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], 'order_amount', 0);
        if ($orderAmount) {
            $openid = $info['openid'];
            $Pay = Payinit::weixin('weixin');
            $Pay->_params = [
                'appid' => $Pay->appId, //APP ID
                'mch_id' => $Pay->mchId, //商户号
                'body' => $body, //商品描述
                'attach' => intval($uid), //附加数据
                'out_trade_no' => $orderSn, //内部订单号
                'total_fee' => $orderAmount, //总金额，单位分
                'trade_type' => 'NATIVE', //交易类型
                'openid' => $openid, //用户标识
                'notify_url' => \config::load('pay', 'weixin', 'notifyUrl'),
            ];
            return $Pay->createNativePayData();
        } else {
            return false;
        }
    }

    public function query($orderNo)
    {
        $Pay = Payinit::weixin('weixin');
        $Pay->_params = [
            'appid' => $Pay->appId, //APP ID
            'mch_id' => $Pay->mchId, //商户号
            'out_trade_no' => $orderNo,
        ];
        return $Pay->orderQuery();
    }

    public function inquiry($order)
    {
        if (substr($order, 0, 2) == 'UO') {
            return servUnionOrder::sole($this->platform)->inquiry($order);
        }
        if ($order[0] == 'O') {
            return \Student\servOrder::sole($this->platform)->inquiry($order);
        }
        return false;
    }
}