<?php


namespace Student;

use _\config;
use _\servAdaptor;
use Core\library\Tool;
use \Student\sign\servWeixin as serWeixin;

class ctrlSess extends \_\ctrlSess
{

    public function runBefore()
    {
        $callbackURI = \input::get('callbackURI', '/')->value();
        $cookieToken = \input::get('cookieToken')->value();
        if ($cookieToken) {
            self::setCookie(self::SESS_COOKIE, $cookieToken);
            header("Location: "  . $callbackURI);
            exit;
        }
        $cipher = \input::get('cipher')->value();
        if ($cipher && $usn = \_\servSession::verify($cipher, \input::ip())) {
            $token = servSession::sole($this->platform, $usn)->start(ctrlSess::flag());
            ctrlSess::setCookie(ctrlSess::SESS_COOKIE, $token);
            $_COOKIE[self::SESS_COOKIE] = $token;
        }


        /*
        $sess = self::checkSess();
        if (servSession::sole($this->platform, $sess['usn'])->check($this->flag, $sess['token']) === false) {
            if ($this->_EXT_ == 'api') {
                $this->apiFailure(self::ERR_UNDEFINED, ['illegal session']);
            }
            /*
            if ($this->client == servAdaptor::CLIENT_WXA) {
                $domain = config::load('boot', 'public', 'domain');
                $scheme = $_SERVER['REQUEST_SCHEME'];
                header("Location: $scheme://$domain/sign-wxa");
                exit;
            }
            if ($this->isWeixin() || Tool::isMobileRequest()) {
                serWeixin::sole($this->platform)->weixinLogin(urlencode($this->_URI_));
            }
        }
        */

        $pres = parent::runBefore();
        return $pres;
    }


    public function _DO_ok()
    {
        echo "<a href='/'>ok</a>";
    }
}