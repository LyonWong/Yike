<?php


namespace _\weixin;


use _\dataUser;
use _\dataUserAuth;
use Core\library\Http;
use Core\unitInstance;

class servMip extends serv_
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
     * 小程序登录
     * @param $code
     * @param $encryptedData
     * @param $iv
     * @param $originKey
     * @return bool|int
     * @throws \coreException
     */
    public function login($code, $encryptedData, $iv, $originKey)
    {

        $appId = \config::load('weixin', 'wxa', 'AppId', '', '_');
        $secret = \config::load('weixin', 'wxa', 'AppSecret', '', '_');

        $ret = $this->getSessionKey($appId, $secret, $code);
        $sessionKey = $ret['session_key'] ?? false;

        if (!$sessionKey) {
            \output::debug('weixin', $ret, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
            return false;
        }

        $res = new WXBizDataCrypt($appId, $sessionKey);
        $errCode = $res->decryptData($encryptedData, $iv, $data);
        if ($errCode != 0) {
            return 'error:' . $errCode;
        }

        $uid = 0;
        $userInfo = json_decode($data, true);
        if (isset($userInfo['unionId'])) {
            $userInfo['headimgurl'] = $userInfo['avatarUrl'];
            $userInfo['nickname'] = $userInfo['nickName'];
            $userInfo['unionid'] = $userInfo['unionId'];
            unset($userInfo['openId'], $userInfo['unionId'], $userInfo['nickName'], $userInfo['avatarUrl'], $userInfo['watermark']);

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


    /**
     * code 换取 session_key
     * @param $appId
     * @param $secret
     * @param $code
     * @return array
     */
    public function getSessionKey($appId, $secret, $code)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appId&secret=$secret&js_code=$code&grant_type=authorization_code";
        $ret = Http::inst()->get($url);
        $ret = json_decode($ret, true);
        return $ret;

    }

}

class WXBizDataCrypt
{
    private $appid;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid, $sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $this->appid = $appid;
    }


    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($encryptedData, $iv, &$data)
    {
        if (strlen($this->sessionKey) != 24) {
            return ErrorCode::$IllegalAesKey;
        }
        $aesKey = base64_decode($this->sessionKey);


        if (strlen($iv) != 24) {
            return ErrorCode::$IllegalIv;
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return ErrorCode::$IllegalBuffer;
        }
        if ($dataObj->watermark->appid != $this->appid) {
            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;
    }

}

/**
 * error code 说明.
 * <ul>
 *    <li>-41001: encodingAesKey 非法</li>
 *    <li>-41003: aes 解密失败</li>
 *    <li>-41004: 解密后得到的buffer非法</li>
 *    <li>-41005: base64加密失败</li>
 *    <li>-41016: base64解密失败</li>
 * </ul>
 */
class ErrorCode
{
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
}