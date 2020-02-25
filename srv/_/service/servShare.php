<?php


namespace _;

use _\weixin\serv;
use Core\library\Http;
use Core\unitInstance;

class servShare extends serv_
{
    use unitInstance;

    protected $APP_ID;
    protected $APP_SECRECT;

    /**
     * @return self
     */
    public static function inst()
    {
        return self::_singleton();
    }

    public function __construct()
    {
        $this->APP_ID = config::load('weixin', 'mp', 'AppID', '', '_');
        $this->APP_SECRECT = config::load('weixin', 'mp', 'AppSecret', '', '_');
    }

    /**
     * 微信分享页面配置信息
     * @param $url string 分享页面的url
     * @return array
     */
    public function wxGetConfig($url)
    {
        $timestamp = time();
        return [
            'appId' => $this->APP_ID,
            'timestamp' => $timestamp,
            'nonceStr' => md5($timestamp),
            'signature' => self::wxGetSignature($timestamp, $url),
            'jsApiList' => ['onMenuShareAppMessage', 'onMenuShareTimeline'],
            'link' => $url
        ];
    }

    protected function wxGetSignature($timestamp, $url)
    {
        $ticket = self::wxGetJsapiTicket();
        $noncestr = md5($timestamp);
        $signature = sha1('jsapi_ticket=' . $ticket . '&noncestr=' . $noncestr . '&timestamp=' . $timestamp . '&url=' . $url);
        return $signature;
    }

    /**
     * 微信获取JsapiTicket（分享链接自定义用）
     * 有效期2小时
     * @return string
     */
    protected function wxGetJsapiTicket()
    {
        $redis = data::redis();
        if (empty ($redis->get('js_api_ticket'))) {
            $accessToken = $this->getAccessToken();
            $str = Http::inst()->get('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi');
            $ticket = json_decode($str, true)['ticket'];
            $redis->set('js_api_ticket', $ticket, 7199);// 比7200小一秒
        } else {
            $ticket = $redis->get('js_api_ticket');
        }

        return $ticket;
    }

    /**
     * 公众号
     * 获取普通Access Token
     * 有效期2小时
     * @return string
     */
    protected function getAccessToken()
    {
        return serv::sole($this->platform)->getAccessToken('mp');
        /*
        $redis = data::redis();
        if (empty ($redis->get('access_token'))) {
            return $this->saveAccessToken();
        } else {$redis->get('access_token')
            $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $redis->get('access_token');
            $ret = Http::inst()->get($url);
            $result = json_decode($ret, true);
            if (isset($result['errcode']) && $result['errcode'] == 40001) {
                return $this->saveAccessToken();
            }
            return $redis->get('access_token');
        }
        */

    }

    protected function saveAccessToken()
    {
        $redis = data::redis();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->APP_ID  . "&secret=" . $this->APP_SECRECT;
        $ret = Http::inst()->get($url);
        $result = json_decode($ret, true);
        // save to Redis
        $redis->set('access_token', $result ["access_token"], 7199);// 比7200小一秒
        return $result ["access_token"];
    }

}
