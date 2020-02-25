<?php


namespace _\sign;


use _\dataUserAuth;
use Core\unitInstance;
use Core\library\Http;

class servWeixin extends serv_
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

    public function wxm($code, $originKey = "_")
    {
        $weixin = \config::load('weixin', 'mp');
        $res = Http::inst()->get('https://api.weixin.qq.com/sns/oauth2/access_token', [
            'appid' => $weixin['AppID'],
            'secret' => $weixin['AppSecret'],
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        $res = json_decode($res, true);
        if (empty($res['access_token'])) {
            $res['sign-code'] = $code;
            \output::debug('weixin', json_encode($res));
            return false;
        }
        $info = Http::inst()->get('https://api.weixin.qq.com/sns/userinfo', [
                'access_token' => $res['access_token'],
                'openid' => $res['openid'],
                'lang' => 'zh_CN'
            ]
        );
        $info = json_decode($info, true);
        $info['name'] = $info['nickname'];
        $info['avatar'] = $info['headimgurl'];
        unset($info['nickname'], $info['headimgurl']);

        $uaid = $info['unionid'];
        $openId = $info['openid'];

        // 有公众号授权记录直接返回
        if ($auth = $this->check(dataUserAuth::TYPE_WEIXIN, $uaid)) {
            if (!$auth['code']) {
                dataUserAuth::sole($this->platform)->update(
                    ['code' => $openId],
                    ['i_type' => dataUserAuth::TYPE_WEIXIN, 'uid' => $auth['uid']]
                );
                $this->save($auth['uid'], $info);
            }
            return $auth['uid'];
        }

        // 尝试关联小程序授权记录
        if ($uid = $this->assoc(dataUserAuth::TYPE_WEIXIN, $uaid, $openId, dataUserAuth::TYPE_WXA)) {
            $this->save($uid, $info); // 更新用户信息(openid)
            return $uid;
        }

        // 创建新用户
        $uid = $this->create(dataUserAuth::TYPE_WEIXIN, $uaid, $openId, $info['name'], $originKey, $info);
        return $uid;
    }

    public function wxa($code, $encryptedData, $iv, $originKey = "_")
    {
        if ($code == 'the code is a mock one') {
            $uaid = base64_encode($code);
            $openId = 'mocked';
        } else {
            $weixin = \config::load('weixin', 'wxa');
            $res = Http::inst()->get('https://api.weixin.qq.com/sns/jscode2session', [
                'appid' => $weixin['AppID'],
                'secret' => $weixin['AppSecret'],
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ]);
            $res = json_decode($res, true);
            $aesKey = base64_decode($res['session_key']);
            $aesIV = base64_decode($iv);
            $aesCipher = base64_decode($encryptedData);
            $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
            $userInfo = json_decode($result, true);
            if ($userInfo['watermark']['appid'] != $weixin['AppID']) {
                return false;
            }
            $uaid = $userInfo['unionId'];
            $openId = $userInfo['openId'];
        }

        $userInfo['name'] = $userInfo['nickName'] ?? '佚名';
        $userInfo['avatar'] = $userInfo['avatarUrl'] ?? \view::upload('user/default/avatar');
        $userInfo['sex'] = $userInfo['gender'] ?? 0;
        unset($userInfo['nickName'], $userInfo['avatarUrl'], $userInfo['watermark']);

        // 有小程序登录授权直接返回
        if ($auth = $this->check(dataUserAuth::TYPE_WXA, $uaid)) {
            return $auth['uid'];
        }

        // 尝试关联微信公众号授权
        if ($uid = $this->assoc(dataUserAuth::TYPE_WXA, $uaid, $openId, dataUserAuth::TYPE_WEIXIN)) {
            return $uid;
        }

        $uid = $this->create(dataUserAuth::TYPE_WXA, $uaid, $openId, $userInfo['name'], $originKey, $userInfo);
        return $uid;
    }

    public function weixinLogin($callbackURI)
    {
        $appId = \config::load('weixin', 'mp', 'AppID', '', '_');
        $request_uri = self::buildRequestUrl($callbackURI);
        header("Location: " . 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appId . '&redirect_uri=' . $request_uri . '#wechat_redirect');
        exit;
    }

    // 拼接uri
    public static function buildRequestUrl($callbackURI)
    {

        $request_uri = urlencode(\config::load('weixin', 'callback', 'mpLogin') . '?callbackURI=' . urlencode($callbackURI));
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

}