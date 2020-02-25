<?php


namespace _\weixin;

use _\config;
use _\data;
use _\dataUser;
use _\dataUserAuth;
use _\servOrigin;
use _\servQiniu;
use _\servUser;
use Core\library\Http;

class serv_ extends \_\serv_
{

    /**
     * @param $userInfo
     * @param string $originKey 缺省'_'表示默认来源
     * @return int
     */
    public function saveAccount($userInfo, $originKey = '_')
    {
        $userInfo['name'] = $userInfo['nickname'];
        $userInfo['avatar'] = $userInfo['headimgurl'];
        unset($userInfo['nickname'], $userInfo['headimgurl']);

        $originId = servOrigin::sole($this->platform)->key2id($originKey) ?: servOrigin::sole($this->platform)->checkIn($originKey);
        $userInfo['account_tim'] = 0; //是否同步到Tencent im
        $uid = dataUser::sole($this->platform)->append(dataUser::ROLE_STUDENT, $userInfo['name'], $originId, json_encode($userInfo));
        if ($uid) {
            //转存头像到QINI
            $sn = servUser::sole($this->platform)->uid2usn($uid);
            servQiniu::inst()->fetch($userInfo['avatar'], 'user/' . $sn . '/avatar');

            //add Auth
            $auid = dataUserAuth::sole($this->platform)->fetchOne(['uaid' => $userInfo['unionid']], 'uid', 'uid');
            if ($auid) {
                if ($auid != $uid) {
                    $uid = $auid;
                    $ret = true;
                } else {
                    $ret = true;
                }
            } else {
                $ret = dataUserAuth::sole($this->platform)->append(dataUserAuth::TYPE_WEIXIN, $uid, $userInfo['unionid'], '');
            }
            if (!$ret) {
                return false;
            }
            //同步帐号到Tim
            $ret = $this->account2Tim($uid, $userInfo['name'], $userInfo['avatar']);
            if (isset($ret['ErrorCode']) && $ret['ErrorCode'] == 0) {
                $userInfo['account_tim'] = 1;
                dataUser::sole($this->platform)->update(['info' => json_encode($userInfo)], ['id' => $uid]);
            }
        }
        return $uid;
    }

    /**
     * 根据code获得用户基本信息
     * @param $code
     * @return bool|mixed
     */
    public function code2Info($code, $section)
    {
        $appId = \config::load('weixin', $section, 'AppID');
        $appSecret = \config::load('weixin', $section, 'AppSecret');
        if (!$code) {
            return false;
        }
        $res = Http::inst()->get('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appId . '&secret=' . $appSecret . '&code=' . $code . '&grant_type=authorization_code');
        $res = json_decode($res, true);
        if (isset($res['access_token'])) {
            $info = Http::inst()->get('https://api.weixin.qq.com/sns/userinfo?access_token=' . $res['access_token'] . '&openid=' . $res['openid'] . '&lang=zh_CN');

        } else {
            return false;
        }
        return $info;
    }

    /**
     * 公众号
     * 根据openId获取用户基本信息
     * @param $openId
     * @param string $type
     * @return bool|mixed
     */
    public function info($openId, $type='mp')
    {
        $accessToken = $this->getAccessToken($type);
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openId . '&lang=zh_CN';
        $ret = Http::inst()->get($url);
        $info = json_decode($ret, true);
        if (isset($info['unionid'])) {
            $info['name'] = $info['nickname'] ?? '佚名';
            $info['avatar'] = $info['headimgurl'] ?? \view::upload('user/default/avatar');
            unset($info['nickname'], $info['headimgurl']);
            return $info;
        }
        return false;
    }

    /**
     * 公众号
     * 获取普通Access Token
     * 有效期2小时
     * @return string
     */
    protected function _getAccessToken()
    {
        $redis = data::redis();
        if (empty ($redis->get('access_token'))) {
            return $this->saveAccessToken();
        } else {
            $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $redis->get('access_token');
            $ret = Http::inst()->get($url);
            $result = json_decode($ret, true);
            if (isset($result['errcode']) && $result['errcode'] == 40001) {
                return $this->saveAccessToken();
            }
            return $redis->get('access_token');
        }
    }

    protected function saveAccessToken()
    {
        $appId = \config::load('weixin', 'mp', 'AppID', '', '_');
        $appSecret = \config::load('weixin', 'mp', 'AppSecret', '', '_');
        $redis = data::redis();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appId . "&secret=" . $appSecret;
        $ret = Http::inst()->get($url);
        $result = json_decode($ret, true);
        // save to Redis
        $redis->set('access_token', $result ["access_token"], 7199);// 比7200小一秒
        return $result ["access_token"];
    }

    public function getAccessToken($type='mp')
    {
        $rkey = "wx_access_token_$type";
        $redis = data::redis();
        if (!$token = $redis->get($rkey)) {
            $address = config::load('weixin', 'base', 'FetchAccessAddress');
            $secrets = config::load('weixin', 'base', 'FetchAccessSecrets');
            $res = Http::inst()->post("$address-$type", [
                'secret' => end($secrets)
            ]);
            $result = json_decode($res, true);
            if ($result['error'] === '0') {
                $token = $result['data']['token'];
                $redis->set($rkey, $token, $result['data']['expire'] - time());
            } else {
                return false;
            }
        }
        return $token;
    }

    public function fetchAccessToken($type)
    {
        $appId = \config::load('weixin', $type, 'AppID', '', '_');
        $appSecret = \config::load('weixin', $type, 'AppSecret', '', '_');
        $rkey = "wx_access_token_$type";
        $redis = data::redis();
        if (!$token = $redis->get($rkey)) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential";
//            $ret = Http::inst()->get($url, [ 'appid' => $appId, 'secret' => $appSecret ]);
            $ret = file_get_contents("$url&appid=$appId&secret=$appSecret");
            $result = json_decode($ret, true);
            $token = $result['access_token'];
            $redis->set($rkey, $token, 7100); // 提前100s失效
        }
        $ttl = $redis->ttl($rkey) - 100; // 预留100s
        return [
            'token' => $token,
            'ttl' => $ttl,
            'expire' => time() + $ttl,
        ];
    }

    /**
     * 公众号
     * 生成带参数的关注公众号二维码链接
     * @param $usn
     * @return array
     */
    public function getTicketUrl($usn)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getAccessToken('mp');
        $params = [
            'expire_seconds' => 60,
            'action_name' => "QR_SCENE",
            'action_info' => [
                'scene' => [
                    'scene_id' => $usn
                ],
            ],
        ];
        $ret = Http::inst()->post($url, json_encode($params));
        $result = \json_decode($ret, true);
        $ticket = $result['ticket'] ?? '';
        return ['url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket, 'effective_time' => date('Y-md- H:i:s', time() + 60)];
    }
}