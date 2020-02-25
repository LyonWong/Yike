<?php


namespace Teacher;


use Core\library\Tool;

class ctrlSess extends \_\ctrlSess
{
    public function runBefore()
    {
        $callbackURI = \input::get('callbackURI', '/')->value();
        $cookieToken = \input::get('cookieToken')->value();
        if ($cookieToken) {
            self::setCookie(self::SESS_COOKIE, $cookieToken);
            self::cookieTime();
            header("Location: " . $callbackURI);
            return true;
        }
        parent::runBefore();
        $status = servTeacher::sole($this->platform)->uid2status($this->uid);
        if ($this->uid && $status == null) {
            parse_str(parse_url($callbackURI)['query'] ?? '', $params);
            $token = $params['token'] ?? null;
            if (!$token) {
                header("Location: /sign-up");
                exit;
            } else {
                header("Location: /apply?token=" . $token);
                exit;
            }

        }
        return true;
    }

    function cookieTime()
    {
        $key = 'KILL_COOKIE' . Tool::getClientIp(true);
        data::redis()->set($key, $key, 60 * 5);
    }

}