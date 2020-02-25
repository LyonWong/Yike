<?php


namespace Admin;


use Admin\sign\servEmail;
use Core\unitAjax;
use Core\unitDoAction;
use Core\unitHttp;
use Core\library\Tool;

class ctrlSign extends ctrlSess
{
    use unitDoAction;
    use unitHttp;
    use unitAjax;

    public function runBefore()
    {
        return true;
    }

    public function _DO_in()
    {
        $callbackURI = \input::get('callbackURI')->value();
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        $showCreate = Tool::IPcheck($ip, $allowedIPs);
        \view::tpl('/sign/in')
            ->with('showCreate', $showCreate)
            ->with('callbackURI', $callbackURI);
    }

    public function _POST_in()
    {
        $email = \input::post('account')->value();
        $password = \input::post('password')->value();
        if ($uid = servEmail::verify($email, $password)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->setCookie(ctrlSess::SESS_COOKIE, $token);
            $this->ajaxSuccess();
        } else {
            $this->ajaxFailure();
        }
    }


    public function _DO_prepare()
    {
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        if (!Tool::IPcheck($ip, $allowedIPs)) {
            \viewException::halt("Forbidden IP", 403);
        }
        \view::tpl('/sign/prepare');
    }

    public function _POST_prepare()
    {
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        if (!Tool::IPcheck($ip, $allowedIPs)) {
            $this->ajaxFailure("Forbidden IP");
        }
        $email = \input::post('email')->value();
        if (servEmail::prepare($email)) {
            $this->ajaxSuccess();
        } else {
            $this->ajaxFailure("Faild to send Email");
        }
    }

    public function _DO_forgot()
    {
        \view::tpl('/sign/forgot');
    }

    public function _POST_forgot()
    {
        $email = \input::post('email')->value();
        if (!servSign::checkAccount($email)) {
            $this->ajaxResponse(-1, "Email not exists", null);
        } else if (servSign::forgot($email)) {
            $this->ajaxSuccess();
        } else {
            $this->ajaxFailure();
        }
    }

    public function _DO_reset()
    {
        $token = \input::get('token')->value();
        $email = servSign::verToken($token);
        \view::tpl('/sign/reset')
            ->with('email', $email)
            ->with('token', $token);
    }

    public function _POST_reset()
    {
        $token = \input::post('token')->value();
        $email = servSign::verToken($token);
        $password = \input::post('password')->value();
        if (servSign::checkPassword($password) == false) {
            $error = "Too simple Password.";
        }
        if (empty($error)) {
            servSign::resetPassword($email, $password);
            $this->ajaxSuccess();
        } else {
            $this->ajaxFailure($error);
        }
    }

    public function _DO_up()
    {
        $token = \input::get('token')->value();
        $email = servEmail::verToken($token);
        \view::tpl('/sign/up')
            ->with('email', $email)
            ->with('token', $token);
    }

    public function _POST_up()
    {
        $token = \input::post('token')->value();
        $email = servEmail::verToken($token);
        $password = \input::post('password')->value();
        if (!$email) {
            $this->ajaxFailure("Invalid Account or Token.");
        }
        if (servEmail::checkEmailFormat($password) == false) {
            $this->ajaxFailure("Illegal Password format");
        }
        if (!servEmail::create($email, $password)) {
            $this->ajaxFailure("Unknown Register error.");
        }
        $this->ajaxSuccess();
    }

    public function _DO_out()
    {
        $sess = self::checkSess();
        servSession::sole($this->platform,$sess['usn'])->close();
        $this->setCookie(ctrlSess::SESS_COOKIE, null, 0);
        $this->httpLocation('/sign-in');
    }

}