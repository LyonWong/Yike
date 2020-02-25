<?php


namespace _\weixin;


use Core\library\Http;
use Core\unitInstance;

class serv extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function textSecCheck($text)
    {
        $token = $this->getAccessToken('wxa');
        $data = json_encode([
            'content' => $text
        ], JSON_UNESCAPED_UNICODE);
        $res = Http::inst()->post('https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $token, $data);
        $dres = json_decode($res);
//        echo $text;print_r($dres);exit;
        return $dres->errcode;
    }

    public function imageSecCheck($file)
    {
        $token = $this->getAccessToken('wxa');
        $data = [
            'media' => curl_file_create($file)
        ];
        $res = Http::inst()->post('https://api.weixin.qq.com/wxa/img_sec_check?access_token=' . $token, $data);
        $dres = json_decode($res);
        if (is_object($dres)) {
            return $dres->errcode;
        } else {
            return false;
        }
    }

}