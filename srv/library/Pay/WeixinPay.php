<?php


class WeixinPay
{
    const VERIFIED = '_verify_';

    const API_PAY = 'https://api.mch.weixin.qq.com/pay/';

    const SEC_API_PAY = 'https://api.mch.weixin.qq.com/secapi/pay/';

    protected $appId;
    protected $mchId;
    protected $signKey;
    protected $signType;
    protected $keyPath;
    protected $certPath;
    protected $log;

    public static function inst($options)
    {
        $inst = new self;
        foreach ($options as $key => $opt) {
            $inst->$key = $opt;
        }
        return $inst;
    }

    public function unifiedorder(array $params)
    {
        return $this->post(self::API_PAY.'unifiedorder', $this->sign($params));
    }

    public function refund(array $params)
    {
        return $this->post(self::SEC_API_PAY.'refund', $this->sign($params), true);
    }

    protected function sign($data)
    {
        $data['appid'] = $this->appId;
        $data['mch_id'] = $this->mchId;
        $data['nonce_str'] = md5(microtime(true));
        $data['sign_type'] = $this->signType;
        $data['sign'] = $this->_sign($data);
        return $data;
    }

    public function _sign($data)
    {
        ksort($data);
        $_sign = '';
        foreach ($data as $key => $val) {
            $_sign .= "$key=$val&";
        }
        $_sign .= "key=$this->signKey";
        switch ($this->signType) {
            case 'MD5':
            default:
                $sign = strtoupper(md5($_sign));
                break;
        }
        return $sign;
    }

    protected function post($url, $data, $withCert=false)
    {
        $curl = curl_init($url);
        if ($withCert) {
            //使用证书：双向验证
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->certPath);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->keyPath);
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataToXml($data));
        $result = curl_exec($curl);
        if ($this->log) {
            file_put_contents($this->log, '[' . date('Y-m-d H:i:s') . ']' . json_encode($data)."\n".$result."\n", FILE_APPEND);
        }
        return $this->xmlToData($result);
    }

    public function verify($result)
    {
        $sign = $result['sign'];
        unset($result['sign']);
        if ( $sign == $this->_sign($result) &&
            $result['return_code'] == 'SUCCESS' &&
            $result['result_code'] == 'SUCCESS'
        ) {
            return true;
        } else {
            return false;
        }
    }

    protected function result($result, array $fields)
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $result[$field] ?? null;
        }
        return $data;
    }

    public function dataToXml($data)
    {
        $xml = "<xml>\n";
        foreach ($data as $key => $val) {
            $xml .= "<$key>$val</$key>\n";
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function xmlToData($xml)
    {
        libxml_disable_entity_loader(true);
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($obj), true);
        return array_filter($data, function ($v) {
            return $v !== [];
        });
    }


}