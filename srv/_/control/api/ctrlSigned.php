<?php


namespace _\api;


use _\ctrlSess;
use _\servSession;
use Core\library\Language;

class ctrlSigned extends ctrlSess
{
    public function runBefore()
    {
        $sess = self::checkSess();
        if (!$this->uid = servSession::sole($this->platform, $sess['usn'])->check2uid($this->flag, $sess['token'])) {
            $this->apiFailure(self::ERR_ILLEGAL_SESSION);
        }
        $this->usn = $sess['usn'];
        servSession::$lang = \input::cookie('lang', Language::detect())->value();
        return true;
    }

    public function _DO_out()
    {
        self::setCookie(self::SESS_COOKIE, '', time()-1);
        $this->apiSuccess();
    }

}