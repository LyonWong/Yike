<?php


namespace _;


use Core\library\Tool;

class ctrlEntry extends ctrl_
{
    public $_page_;

    public $platform=null;

    public function run()
    {
        //有密令时登录
        $cipher = \input::get('cipher')->value();
        $source = \input::get('source')->value();
        $version = \input::get('version')->value();
        $clientUser = \input::cookie(serv_::COOKIE_CLIENT_USER)->value();
        $clientSess = \input::cookie(serv_::COOKIE_CLIENT_SESS)->value();
        if ($cipher && $usn = servSession::verify($cipher, \input::ip())) {
            $token = servSession::sole($this->platform, $usn)->start(ctrlSess::flag());
            ctrlSess::setCookie(ctrlSess::SESS_COOKIE, $token);
        }
        if ($source) {
            \output::cookie('source', $source, null, '/');
        }
        if ($version) {
            setcookie('version', $version, 0, '/');
        }
        if (!$clientUser) { // 设置长期有效的用户标记
            $ip = \input::ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            $rn = Tool::genSecret(16);
            $cu = md5($ip.$ua.$rn);
            \output::cookie(serv_::COOKIE_CLIENT_USER, $cu, strtotime("+99 years"), '/');
        }
        if (!$clientSess) { // 设置当前会话标记
            \output::cookie(serv_::COOKIE_CLIENT_SESS, Tool::genSecret(8, Tool::STR_FORMAL), null, '/');
        }
        \view::tpl("entry/$this->_page_");
    }

}