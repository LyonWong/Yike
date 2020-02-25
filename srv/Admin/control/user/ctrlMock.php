<?php


namespace Admin\user;


use _\config;
use Admin\servSession;
use Core\unitHttp;

class ctrlMock extends ctrl_
{
    use unitHttp;

    public function _DO_()
    {
        $usn = $this->apiRequest('usn');
        $token = servSession::sole($this->platform, $usn)->start($this->flag);
        $this->setCookie(self::SESS_COOKIE, $token);
        $URL = $_SERVER['REQUEST_SCHEME'] . "://" . config::load('boot', 'public', 'domain', '_');
        $this->httpLocation($URL);
    }

}