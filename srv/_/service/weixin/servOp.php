<?php


namespace _\weixin;


use _\dataUser;
use _\dataUserAuth;
use Core\unitInstance;

class servOp extends serv_
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

    /**
     * 微信扫码登录
     * @param $code
     * @param $originKey
     * @return bool|int
     */
    public function weixinLogin($code, $originKey)
    {
        $userInfo = $this->code2Info($code,'web');
        if (!$userInfo) {
            return false;
        }
        $uid = 0;
        $userInfo = json_decode($userInfo, true);
        if (isset($userInfo['unionid'])) {
            unset($userInfo['openid']);
            $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_WEIXIN, $userInfo['unionid']);
            if (!$res) {
                $uid = $this->saveAccount($userInfo, $originKey);
            } else {
                dataUser::sole($this->platform)->update(['tms_update' => date('Y-m-d H:i:s')], ['id' => $res['uid']]);
                $uid = $res['uid'];
            }
        }
        return $uid;
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