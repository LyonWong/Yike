<?php


namespace _\api;


use _\ctrlSess;
use _\servSession;
use _\servUser;
use _\sign\servWeixin;

class ctrlSign extends ctrl_
{
    public function _DO_in()
    {}

    public function _DO_out()
    {
        $res = ctrlSess::setCookie(ctrlSess::SESS_COOKIE, '', time()-1);
        $this->apiSuccess($res);
    }

    public function _POST_wxa()
    {
        $code = $this->apiPOST('code');
        $userInfo = $this->apiPOST('userInfo');
        $origin = $this->apiPOST('origin', '_');
        $uid = servWeixin::sole($this->platform)->wxa($code, $userInfo, $origin);
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        $token = servSession::sole($this->platform, $usn)->start(ctrlSigned::flag());
        $data = [
            'usn' => $usn,
            'token' => $token,
        ];
        $this->apiSuccess($data);
    }

}