<?php


namespace Student\sign;

use Core\unitHttp;
use Student\serv_;
use Core\unitInstance;

class servWeixin extends serv_
{
    use unitHttp;
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function weixinLogin($callbackURI)
    {
        $appId = \config::load('weixin', 'mp', 'AppID', '', '_');
        $request_uri = self::buildRequestUrl($callbackURI);
        self::httpLocation('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appId . '&redirect_uri=' . $request_uri . '#wechat_redirect');
        exit;
    }

    // 拼接uri
    public static function buildRequestUrl($callbackURI)
    {

        $request_uri = urlencode(\config::load('weixin', 'callback', 'mpLogin') . '?callbackURI=' . $callbackURI);
        if (stripos($request_uri, '%2f') === false) {
            $request_uri .= '?';
        } else {
            $request_uri .= '&';
        }
        $tmpurldata = array(
            "response_type" => "code",
            "scope" => "snsapi_userinfo",
            "state" => 1
        );
        $request_uri .= http_build_query($tmpurldata);
        return $request_uri;
    }

    public function weixinInfo($callbackURI)
    {
        $ret['appid'] = \config::load('weixin', 'web', 'AppID', '', '_');
        $ret['redirect_uri'] = \config::load('weixin', 'callback', 'webLogin') . '?callbackURI=' . urlencode($callbackURI);
        $ret['state'] = 'STATE';
        $ret['href'] = \config::load('boot', 'public', 'assets');
        return $ret;
    }

}
