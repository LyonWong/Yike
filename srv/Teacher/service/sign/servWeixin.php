<?php


namespace Teacher\sign;

use Core\unitHttp;
use _\serv_;
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

    public function weixinInfo($callbackURI)
    {
        $ret['appid'] = \config::load('weixin', 'web', 'AppID', '', '_');
        $ret['redirect_uri'] = \config::load('weixin', 'callback', 'webLogin') . '?callbackURI=' . urlencode($callbackURI);
        $ret['state'] = 'STATE';
        $ret['href'] = \config::load('boot', 'public', 'assets');
        return $ret;
    }

}
