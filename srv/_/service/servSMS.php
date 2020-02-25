<?php


namespace _;

require_once PATH_ROOT . '/library/sms/SmsSender.php';

use sms\SmsSingleSender;
use Core\unitResult;
use Core\unitInstance;
use Core\library\Tool;

class servSMS extends serv_
{
    use unitInstance;

    const TEMPLATE_BIND = 18619;

    const BIND_TEL = 'BIND_TEL_';
    const BIND_CD = 'BIND_CD_';

    protected $AppId;
    protected $AppKey;
    protected $SdkAppId;


    const CHANNEL_TEMPLID = [
        'bind' => '18619',
    ];

    const CODE_ACTIVE_MINUTE = 10; //短信验证码有效时间，单位分钟

    //发送短信限制 5分钟内发送10次
    const SEND_SMS_LIMIT = 10;
    const SEND_SMS_LIMIT_TIME = 60 * 5;

    const ERROR_QUEST_TOO_FREQUENTLY = '发送短信太过频繁, 休息下吧';
    const ERROR_SEND_FAILED = '发送失败，请联系客服';
    const ERROR_CHANNEL_NOT_EXIST = 'channel not exist';
    const ERROR_SMS_CODE = '验证码错误';

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->AppId = config::load('sms', 'tencent', 'AppId', null, '_');
        $this->AppKey = config::load('sms', 'tencent', 'AppKey', null, '_');
    }

    public function sendOne($telephone, $message, $template = null)
    {
        $sender = new SmsSingleSender($this->AppId, $this->AppKey);
        list($regionCode, $phoneNumber) = explode('-', $telephone);
        if ($template) {
            $res = $sender->sendWithParam($regionCode, $phoneNumber, $template, (array)$message);
        } else {
            $res = $sender->send(0, $regionCode, $phoneNumber, $message);
        }
        $res= json_decode($res, true);
        $result = unitResult::inst();
        if ($res['result'] == 0 && $res['errmsg'] == 'OK') {
            $result->ok();
        } else {
            $result->err($res['errmsg']);
        }
        \output::debug('sms', [
            'telephone' => $telephone,
            'message' => $message,
            'template' => $template,
        ], DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        return $result;
    }

    /**
     * 发送验证短信
     * @param $telephone
     * @return unitResult
     */
    public function bindRequest($telephone)
    {
        $token = Tool::genSecret(6, Tool::STR_NUMBER);
        $result = $this->sendOne($telephone, [$token, self::CODE_ACTIVE_MINUTE], self::TEMPLATE_BIND);
        if ($result->error == 0) {
            data::redis()->setex(self::BIND_TEL.$telephone, SECONDS_MINUTE * self::CODE_ACTIVE_MINUTE, $token);
        }
        return $result;
    }

    public function bindCooling($telephone)
    {
        $key = self::BIND_CD.$telephone;
        $ttl = data::redis()->ttl($key);
        if ($ttl < 0) {
            data::redis()->setex($key, SECONDS_MINUTE, 1);
        }
        return $ttl;
    }

    /**
     * 校验手机验证码
     * @param $telephone
     * @param $token
     * @return bool
     */
    public function bindVerify($telephone, $token)
    {
        return $token && data::redis()->get(self::BIND_TEL.$telephone) == $token;
    }

    public function singleSender($phoneNumber, $msg = '', $templId = 0)
    {
        $singleSender = new SmsSingleSender($this->AppId, $this->AppKey);
        if ($templId) {
            $params = [(string)$this->getCode($templId), (string)self::CODE_ACTIVE_MINUTE];
            $result = $singleSender->sendWithParam("86", $phoneNumber, $templId, $params);
        } else {
            $result = $singleSender->send(0, "86", $phoneNumber, $msg);
        }

        $res = \json_decode($result, true);
        if ($res['result'] == 0 && $res['errmsg'] == 'OK') {
            return true;
        }
        return false;
    }


    /**
     * 使用限制处理
     *
     * @param string $key
     * @param int $limit
     * @param int $expire
     * @return boolean
     */
    public function useLimit($key, $limit, $expire = 600)
    {

        $count = (int)(data::redis()->incr($key));
        if ($count === 1 && $expire > 0) {
            data::redis()->expire($key, $expire);
        }
        return (bool)($limit < $count);
    }


    public function getCode($templId)
    {
        $key = 'USE_CODE_KEY_' . $templId . '_' . Tool::getClientIp(true);
        $code = mt_rand(1000, 9999);
        data::redis()->set($key, $code, 60 * self::CODE_ACTIVE_MINUTE);
        return $code;

    }

}