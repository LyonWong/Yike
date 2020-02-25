<?php


namespace _\weixin;

require_once PATH_ROOT . '/library/Pay/WeixinPay.php';

use _\config;
use _\dataOrder;
use _\dataUserAuth;
use _\servOrder;
use _\servOrders;
use Core\unitInstance;
use Core\unitResult;

class servPay extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);

    }

    public function prepay($iWay, $sn, $useBalance=true)
    {
        $way = servOrders::MAP_WAY[$iWay];
        $options = config::load('pay', $way);
        $result = unitResult::inst();
        $order = servOrders::sole($this->platform)->inquiry($sn);
        $iStatus = array_search($order['status'], servOrder::STATUS_MAP);
        if ($iStatus > 0) {
            return $result->err("该订单已支付");
        }
        if (!$openId = $this->getOpenId($iWay, $order['uid'])) {
            return $result->err("无法获取OpenId");
        }

        $srvPay = \WeixinPay::inst($options);
        $params = [
            'body' => $order['title'],
            'attach' => $order['uid'],
            'out_trade_no' => $sn,
            'total_fee' => $useBalance ? $order['surplus'] : $order['order_amount'],
            'trade_type' => $options['tradeType'] ?? 'JSAPI',
            'openid' => $openId,
            'notify_url' => $options['notifyUrl'],
            'spbill_create_ip' => \input::ip(),
        ];
        $res = $srvPay->unifiedorder($params);
        if ($res['result_code'] == 'FAIL' && $res['err_code_des'] == '201 商户订单号重复') { // 生成新订单
            $params['out_trade_no'] = servOrders::sole($this->platform)->recreate($sn);
            $res = $srvPay->unifiedorder($params);
        }
        if ($srvPay->verify($res)) {
            $data = [
                'timeStamp' => (string)time(),
                'nonceStr' => md5(microtime(true)),
                'package' => "prepay_id=$res[prepay_id]",
                'signType' => 'MD5',
            ];
            $data['paySign'] = $srvPay->_sign(array_merge([
                'appId' => $options['appId']
            ], $data));
            $data['scanUrl'] = $res['code_url'];
            return $result->ok($data);
        } else {
            return $result->err($res['err_code_des'] ?? $res['return_code']);
        }
    }

    public function resultNotifyWxa($xml)
    {
        $options = \config::load('pay', 'wxa');
        $srvPay = \WeixinPay::inst($options);
        $data = $srvPay->xmlToData($xml);
        if ($srvPay->verify($data)) {
            return (bool)servOrders::sole($this->platform)->charge($data['out_trade_no'], $data['transaction_id'], $data['total_fee'], servOrders::PAY_WAY_WXA);
        } else {
            return false;
        }
    }

    public function refundWxa($sn, $refundSn, $totalAmount, $refundAmount)
    {
        $options = config::load('pay', 'wxa');
        $srvPay = \WeixinPay::inst($options);
        $params = [
            'out_trade_no' => $sn,
            'out_refund_no' => $refundSn,
            'total_fee' => $totalAmount,
            'refund_fee' => $refundAmount,
        ];
        $res = $srvPay->refund($params);
        return $srvPay->verify($res);
    }

    public function refund($iWay, $sn, $refundSn, $totalAmount, $refundAmount, $refundDesc=null)
    {
        $way = servOrders::MAP_WAY[$iWay];
        $options = config::load('pay', $way);
        $srvPay = \WeixinPay::inst($options);
        $params = [
            'out_trade_no' => $sn,
            'out_refund_no' => $refundSn,
            'total_fee' => $totalAmount,
            'refund_fee' => $refundAmount,
        ];
        if ($refundDesc) {
            $params['refund_desc'] = $refundDesc;
        }
        $res = $srvPay->refund($params);
        return $srvPay->verify($res);
    }


    public function getOpenId($iWay, $uid)
    {
        switch ($iWay) {
            case servOrders::PAY_WAY_WXM:
                $openId = dataUserAuth::sole($this->platform)->fetchCodeByUid(dataUserAuth::TYPE_WEIXIN, $uid);
                break;
            case servOrders::PAY_WAY_WXA:
                $openId = dataUserAuth::sole($this->platform)->fetchCodeByUid(dataUserAuth::TYPE_WXA, $uid);
                break;
            case servOrders::PAY_WAY_WXS:
                $openId = dataUserAuth::sole($this->platform)->fetchCodeByUid(dataUserAuth::TYPE_WEIXIN, $uid);
                break;
            default:
                $openId = null;
        }
        return $openId;
    }

    public function ver($xml)
    {
        $options = config::load('pay', 'wxa');
        $srvPay = \WeixinPay::inst($options);

    }
}