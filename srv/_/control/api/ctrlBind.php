<?php


namespace _\api;


use _\dataUserAuth;
use _\servSMS;
use _\sign\servTelephone;

class ctrlBind extends ctrlSigned
{
    const ERR_COOLING_DOWN = [1, '请%s秒后再试'];
    const ERR_TELNO_HAS_BEEN_USED = [2, '手机号已被使用。'];

    public function _POST_telephone($opt)
    {
        $telephone = $this->apiPOST('telephone');
        $srv = servSMS::sole($this->platform);
        switch ($opt) {
            case 'request':
                if (dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_TELEPHONE, $telephone)) {
                    $this->apiFailure(self::ERR_TELNO_HAS_BEEN_USED);
                }
                $cd = $srv->bindCooling($telephone);
                if ($cd < 0) {
                    $result = $srv->bindRequest($telephone);
                    if ($result->error == 0) {
                        $this->apiSuccess();
                    } else {
                        $this->apiFailure(self::ERR_UNDEFINED, [$result->message]);
                    }
                } else {
                    $this->apiFailure(self::ERR_COOLING_DOWN, [$cd]);
                }
                break;
            case 'verify':
                $token = $this->apiPOST('token');
                $res = servSMS::sole($this->platform)->bindVerify($telephone, $token);
                if ($res) { // 验证成功，绑定到用户
                    $res = servTelephone::sole($this->platform)->bind($this->uid, $telephone);
                }
                $this->apiSuccess($res);
                break;
            default:
        }
    }

}