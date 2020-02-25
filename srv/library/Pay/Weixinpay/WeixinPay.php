<?php

namespace Pay\Weixinpay;

use Core\library\Tool;

/**
 * 微信支付
 */
class WeixinPay
{

    //支付配置
    public $appId;
    public $mchId;
    public $signType = 'MD5';
    public $key;
    public $notifyUrl;
    public $sslCertPath;
    public $sslKeyPath;

    //支付参数
    public $_params;

    //统一下单url
    const POST_ORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    //订单查询url
    const ORDER_QUERY_URL = 'https://api.mch.weixin.qq.com/pay/orderquery';

    //申请退款url
    const REFUNC_URL = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

    //查询退款url
    const ORDER_REFUND_QUERY_URL = 'https://api.mch.weixin.qq.com/pay/refundquery';

    //企业付款url
    const TRANSFERS_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

    //企业付款查询url
    const TRANSFERS_QUERY_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

    //发放代金券
    const SEND_COUPON_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon';

    //沙箱验签密钥
    const GET_SIGN_KEY = 'https://api.mch.weixin.qq.com/sandboxnew/pay/getsignkey';

    //交易对账单
    const BILL_URL = 'https://api.mch.weixin.qq.com/pay/downloadbill';

    //企业付款到银行卡
    const PAY_BANK_URL = 'https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank';

    //企业付款到银行卡查询
    const PAY_NANK_QUERY_URL = 'https://api.mch.weixin.qq.com/mmpaysptrans/query_bank';

    public function getSignKey()
    {
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xmlStr = $this->arrayToXml();
        $res = $this->postUrl(self::GET_SIGN_KEY, $xmlStr);
        return $this->xmlToArray($res);
    }

    /**
     * 统一下单
     * out_trade_no、body、total_fee、trade_type必填
     * @return array | false
     */
    public function createJsPayData()
    {
        $this->_params['spbill_create_ip'] = Tool::getClientIp();
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xmlStr = $this->arrayToXml();

        $res = $this->postUrl(self::POST_ORDER_URL, $xmlStr);
        $res = $this->xmlToArray($res);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS' && $this->verifySignResponse($res)) {
            $params = [
                'appId' => $this->_params['appid'],
                'timeStamp' => (string)time(),
                'nonceStr' => $this->getRandomStr(),
                'package' => 'prepay_id=' . $res['prepay_id'],
                'signType' => 'MD5'
            ];

            $this->_params = $params;
            $this->_params['paySign'] = $this->sign();
            return $this->_params;
        }
        if ($res['return_code'] == 'FAIL') {
            return false;
            //    throw new \Exception("提交预支付交易单失败:{$res['return_msg']}");
        }

        //throw new \Exception("提交预支付交易单失败1，{$res['err_code']}:{$res['err_code']}");
        return false;
    }

    /**
     * 原生扫码支付，生成直接支付url，支付url有效期为2小时,模式二
     * @return bool|mixed
     */
    public function createNativePayData()
    {
        $this->_params['spbill_create_ip'] = Tool::getClientIp();
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xmlStr = $this->arrayToXml();

        $res = $this->postUrl(self::POST_ORDER_URL, $xmlStr);
        $res = $this->xmlToArray($res);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS' && $this->verifySignResponse($res)) {
            $codeUrl = $res['code_url'];
            return $codeUrl;
        }
        return false;
    }

    /**
     * 查询订单
     * out_trade_no、transaction_id至少填一个
     */
    public function orderQuery()
    {
        $this->_params['spbill_create_ip'] = Tool::getClientIp();
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();
        $response = $this->postUrl(self::ORDER_QUERY_URL, $xml);
        $res = $this->xmlToArray($response);

        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            return $res;
        }
        return false;
    }

    /**
     * 申请退款(双向证书)
     * out_trade_no、transaction_id至少填一个
     * @return bool|array
     */
    public function createRefundData()
    {
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();
        $response = $this->postUrl(self::REFUNC_URL, $xml, true);
        $res = $this->xmlToArray($response);
        file_put_contents('/tmp/refund.log', $response . PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            return true;
        }
        return false;

    }

    /**
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     */
    public function refundQuery()
    {

        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();

        $xml = $this->arrayToXml();
        $response = $this->postUrl(self::ORDER_REFUND_QUERY_URL, $xml);
        $res = $this->xmlToArray($response);

        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS' && $this->verifySignResponse($res)) {
            return $res;
        }
        return false;
    }


    /**
     * 企业付款（双向证书）
     * 给同一个实名用户付款，单笔单日限额2W/2W
     * 不支持给非实名用户打款
     * 一个商户同一日付款总额限额100W
     * 单笔最小金额默认为1元
     * 每个用户每天最多可付款10次，可以在商户平台--API安全进行设置
     * 给同一个用户付款时间间隔不得低于15秒
     *partner_trade_no、check_name 必填
     * @return array|bool
     * ["return_code": "SUCCESS",
     *" return_msg": "",
     *" nonce_str": "54265150dcf7989",
     *" result_code": "SUCCESS",
     *" partner_trade_no": "122344422m33",
     *" payment_no": "1000018301201708089010794245",
     *" payment_time": "2017-08-08 17:09:35"]
     *
     */
    public function createTransferData()
    {
        $this->_params['spbill_create_ip'] = Tool::getClientIp();
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();

        $response = $this->postUrl(self::TRANSFERS_URL, $xml, true);
        $res = $this->xmlToArray($response);
        return $res;
    }


    /**
     * 企业付款订单查询
     * 查询企业付款API只支持查询30天内的订单，30天之前的订单请登录商户平台查询。
     * partner_trade_no 商户订单号 必填
     * @return array|bool
     */
    public function transferQuery()
    {
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();

        $response = $this->postUrl(self::TRANSFERS_QUERY_URL, $xml, true);
        $res = $this->xmlToArray($response);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            return $res;
        }
        return false;
    }

    /**
     * 单商户日限额——单日100w
     * 单次限额——单次5w
     * 单商户给同一银行卡单日限额——单日5w
     * partner_trade_no、enc_bank_no、 enc_true_name、bank_code、amount 必填
     * @return array|bool
     * ["return_code": "SUCCESS",
     *" return_msg": "",
     *" nonce_str": "54265150dcf7989",
     *" result_code": "SUCCESS",
     *" partner_trade_no": "122344422m33",
     *" payment_no": "1000018301201708089010794245",
     *" payment_time": "2017-08-08 17:09:35"]
     *
     */
    public function createPayBankData()
    {
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();

        $response = $this->postUrl(self::PAY_BANK_URL, $xml, true);
        $res = $this->xmlToArray($response);
        return $res;
    }


    /**
     * 发放代金券
     * 请求需要双向证书
     * @return bool
     */
    public function sendCoupon()
    {
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();
        $response = $this->postUrl(self::REFUNC_URL, $xml, true);
        $res = $this->xmlToArray($response);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            return true;
        }
        return false;
    }
    /**
     * 验证异步通知
     * @return boolean
     */
    public function verifyNotify()
    {
        $this->_params = $this->xmlToArray($this->_params);
        if (empty($this->_params['sign'])) {
            return false;
        }
        $sign = $this->sign();
        return $this->_params['sign'] == $sign;
    }

    /**
     * 取成功响应
     * @return string
     */
    public function getSucessXml()
    {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[SUCCESS]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    public function getFailXml()
    {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[FAIL]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 数组转成xml字符串
     *
     * @return string
     */
    protected function arrayToXml()
    {
        $xml = '<xml>';
        foreach ($this->_params as $key => $value) {
            $xml .= "<{$key}>";
            $xml .= "<![CDATA[{$value}]]>";
            $xml .= "</{$key}>";
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * xml 转换成数组
     * @param string $xml
     * @return array
     */
    public function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        $xmlObj = simplexml_load_string(
            $xml,
            'SimpleXMLIterator',   //可迭代对象
            LIBXML_NOCDATA
        );

        $arr = [];
        $xmlObj->rewind(); //指针指向第一个元素
        while (1) {
            if (!is_object($xmlObj->current())) {
                break;
            }
            $arr[$xmlObj->key()] = $xmlObj->current()->__toString();
            $xmlObj->next(); //指向下一个元素
        }

        return $arr;
    }

    //验证统一下单接口响应
    protected function verifySignResponse($arr)
    {
        $tmpArr = $arr;
        unset($tmpArr['sign']);
        ksort($tmpArr);
        $str = '';
        foreach ($tmpArr as $key => $value) {
            $str .= "$key=$value&";
        }
        $str .= 'key=' . $this->key;

        if ($arr['sign'] == $this->signMd5($str)) {
            return true;
        }
        return false;
    }


    /**
     * 签名
     * 规则：
     * 先按照参数名字典排序
     * 用&符号拼接成字符串
     * 最后拼接上API秘钥，str&key=密钥
     * md5运算，全部转换为大写
     *
     * @return string
     */
    protected function sign()
    {
        ksort($this->_params);
        $signStr = $this->arrayToString();
        $signStr .= '&key=' . $this->key;
        if ($this->signType == 'MD5') {
            return $this->signMd5($signStr);
        }

        throw new \InvalidArgumentException('Unsupported sign method');
    }

    /**
     * 数组转成字符串
     * @return string
     */
    protected function arrayToString()
    {
        $params = $this->filter($this->_params);
        $str = '';
        foreach ($params as $key => $value) {
            $str .= "{$key}={$value}&";
        }

        return substr($str, 0, strlen($str) - 1);
    }

    /*
     * 过滤待签名数据，sign和空值不参加签名
     *
     * @return array
     */
    protected function filter($params)
    {
        $tmpParams = [];
        foreach ($params as $key => $value) {
            if ($key != 'sign' && !empty($value)) {
                $tmpParams[$key] = $value;
            }
        }

        return $tmpParams;
    }

    /**
     * MD5签名
     *
     * @param string $str 待签名字符串
     * @return string 生成的签名，最终数据转换成大写
     */
    protected function signMd5($str)
    {
        $sign = md5($str);

        return strtoupper($sign);
    }

    /**
     * 获取随机字符串
     * @return string 不长于32位
     */
    protected function getRandomStr()
    {
        return substr(rand(10, 999) . strrev(uniqid()), 0, 15);
    }

    /**
     * 通过POST方法请求URL
     * @param string $url
     * @param array|string $data post的数据
     *
     * @return mixed
     */
    protected function postUrl($url, $data, $useCert = false)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $useCert); //忽略证书验证
        if ($useCert) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证

            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->sslCertPath);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->sslKeyPath);
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        return $result;
    }

}