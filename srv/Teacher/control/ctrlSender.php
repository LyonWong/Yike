<?php


namespace Teacher;


use _\servSMS;
use Core\library\Tool;

class ctrlSender extends ctrlSess
{
    const ERROR_PHONE_NUMBER = ['5.1.1', '请输入正确的手机号'];
    const ERROR_CHANNEL_NOT_EXIST = ['5.1.2', '%s'];
    const ERROR_QUEST_TOO_FREQUENTLY = ['5.1.3', '%s'];
    const ERROR_SEND_FAILED = ['5.1.4', '%s'];
    const ERROR_SMS_CODE = ['5.2.1', '%s'];

    public function runBefore()
    {
        return true;
    }

    public function _POST_sms()
    {
        $phoneNumber = $this->apiPOST('phone_number');
        $channel = $this->apiPOST('channel');

        if (!Tool::isMobile($phoneNumber)) {
            $this->apiFailure(self::ERROR_PHONE_NUMBER);
        }

        if (!isset(servSMS::CHANNEL_TEMPLID[$channel])) {
            $this->apiFailure(self::ERROR_CHANNEL_NOT_EXIST, [servSMS::ERROR_CHANNEL_NOT_EXIST]);
        }

        if (servSMS::sole($this->platform)->useLimit('USE_LIMIT_' . $channel . '_' . Tool::getClientIp(true), servSMS::SEND_SMS_LIMIT, servSMS::SEND_SMS_LIMIT_TIME)) {
            $this->apiFailure(self::ERROR_QUEST_TOO_FREQUENTLY, [servSMS::ERROR_QUEST_TOO_FREQUENTLY]);
        }

        $ret = servSMS::sole($this->platform)->singleSender($phoneNumber, null, servSMS::CHANNEL_TEMPLID[$channel]);
        if ($ret) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERROR_SEND_FAILED, [servSMS::ERROR_SEND_FAILED]);
        }
    }

    public function _POST_checkSms()
    {
        $channel = $this->apiPOST('channel');
        $code = $this->apiPOST('code');

        if (!isset(servSMS::CHANNEL_TEMPLID[$channel])) {
            $this->apiFailure(self::ERROR_CHANNEL_NOT_EXIST, [servSMS::ERROR_CHANNEL_NOT_EXIST]);
        }
        $key = 'USE_CODE_KEY_' . servSMS::CHANNEL_TEMPLID[$channel] . '_' . Tool::getClientIp(true);
        if (data::redis()->get($key) == $code && $code != '') {
            $this->apiSuccess();
        }

        $this->apiFailure(self::ERROR_SMS_CODE, [servSMS::ERROR_SMS_CODE]);

    }


}