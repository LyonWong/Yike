<?php


namespace _\weixin;


use _\config;
use _\data;
use Core\library\Http;
use Core\library\Tool;
use Core\unitInstance;

class servJS extends serv_
{
    use unitInstance;

    protected $APP_ID;
    protected $APP_SECRET;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->APP_ID = config::load('weixin', 'mp', 'AppID');
        $this->APP_SECRET = config::load('weixin', 'mp', 'AppSecret');
    }

    public function config($url)
    {
        $debug = config::load('weixin', 'mp', 'debug', false);
        $res = $this->signature($url);
        return [
            'debug' => $debug,
            'appId' => $this->APP_ID,
            'timestamp' => $res['timestamp'],
            'nonceStr' => $res['nonce'],
            'signature' => $res['signature'],
        ];
    }

    public function signature($url)
    {
        $nonce = Tool::genSecret(16, Tool::STR_FORMAL);
        $timestamp = time();
        $ticket = $this->getTicket();
        $str = "jsapi_ticket=$ticket&noncestr=$nonce&timestamp=$timestamp&url=$url";
        $sign = sha1($str);
        return [
            'nonce' => $nonce,
            'timestamp' => $timestamp,
            'signature' => $sign
        ];
    }

    protected function getTicket()
    {
        $rkey = 'js_api_ticket';
        $rds = data::redis();
        if (!$ticket = $rds->get($rkey)) {
            $accessToken = $this->getAccessToken();
            $str = Http::inst()->get('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi');
            $res = json_decode($str);
            if ($res->errcode) {
                $ticket = false;
            } else {
                $ticket = $res->ticket;
                $rds->set('js_api_ticket', $ticket, 7100);// 提前100秒过期
            }
        }
        return $ticket;
    }

}