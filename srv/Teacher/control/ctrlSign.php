<?php


namespace Teacher;


use Core\unitAPI;
use Core\unitDoAction;
use Core\unitHttp;
use Core\library\Tool;
use Teacher\sign\servAccount;
use Teacher\sign\servWeixin as servWeixin;

class ctrlSign extends ctrlSess
{
    use unitDoAction;
    use unitHttp;
    use unitAPI;

    const ERR_ACCOUNT_OR_PASSWORD_WRONG = ['1.2.1', 'account or password is wrong'];
    const ERR_ACCOUNT_NOT_EXISTS = ['1.2.2', '邮箱账户不存在'];
    const ERR_ILLEGAL_PASSWORD = ['1.2.3', '不符合要求的密码'];
    const ERR_MAIL_COOLING_DOWN = ['1.2.4', '重试%ss'];

    const KEY_MAIL_CD = 'MAIL_CD_';


    public function runBefore()
    {
        return true;
    }

    public function _DO_in($method='weixin')
    {
        $callbackURI = \input::get('callbackURI')->value();
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        $showCreate = Tool::IPcheck($ip, $allowedIPs);
        $wechatInfo = servWeixin::sole($this->platform)->weixinInfo($callbackURI);
        \view::tpl("/sign/in-$method")
            ->with('showCreate', $showCreate)
            ->with('callbackURI', $callbackURI)
            ->with('wechatInfo',$wechatInfo);
    }

    public function _DO_up()
    {
        $url = Tool::isMobileRequest() ? "sign/mobile-up" : "sign/up";
        \view::tpl($url);
    }

    public function _DO_forget()
    {
        \view::tpl('/sign/forget');
    }

    public function _DO_reset()
    {
        $token = \input::get('token')->value();
        $email = servAccount::inst($this->platform)->checkResetToken($token);
        \view::tpl('/sign/reset')
            ->with('email', $email ?: '无效的重置授权')
            ->with('token', $token);
    }

    public function _POST_up()
    {
        $email = \input::post('account')->value();
        $password = \input::post('password')->value();
        $uid = servAccount::inst($this->platform)->create($email, $password);
        if ($uid) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }


    public function _POST_in()
    {
        $email = \input::post('account')->value();
        $password = \input::post('password')->value();
        if ($uid = servAccount::inst($this->platform)->verify($email, $password)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->setCookie(self::SESS_COOKIE, $token);
            if ($this->_EXT_ == 'api') {
                $this->apiSuccess();
            } else {
                $this->httpLocation('/');
            }
        } else {
            if ($this->_EXT_ == 'api') {
                $this->apiFailure(self::ERR_ACCOUNT_OR_PASSWORD_WRONG);
            } else {
                \view::tpl('/sign/in-email')
                    ->with('hint', "账户或密码错误");
            }
        }
    }

    public function _POST_reset()
    {
        $email = \input::post('account')->value();
        $password = \input::post('password')->value();
        $token = \input::post('token')->value();
        $srvAccount = servAccount::inst($this->platform);
        if (!$srvAccount->checkPasswordFormat($password)) {
            $this->apiFailure(self::ERR_ILLEGAL_PASSWORD);
        }
//        $_email = servAccount::inst($this->platform)->checkResetToken($token);
        if ( $srvAccount->reset($email, $password, $token) ) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["重置失败"]);
        }
    }

    public function _DO_out()
    {
        list($usn) = explode('-', $_COOKIE[ctrlSess::SESS_COOKIE] ?? '-');
        servSession::sole($this->platform,$usn)->close();
        $this->setCookie(ctrlSess::SESS_COOKIE, null, 0);
        $this->httpLocation('/sign-in');
    }

    public function _POST_sendEmail()
    {
        $email = $this->apiPOST('email');
        $key = self::KEY_MAIL_CD.$email;
        $ttl = data::redis()->ttl($key);
        if ($ttl > 0) {
            $this->apiFailure(self::ERR_MAIL_COOLING_DOWN, [$ttl]);
        } else {
            data::redis()->setex($key, SECONDS_MINUTE, 1);
        }
        $ret = \Admin\servTeacher::sole($this->platform)->sendInviteEmail($email);
        if($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED, ["发送失败"]);
    }

    public function _POST_email($opt)
    {
        $email = $this->apiPOST('email');
        $srv = servAccount::inst($this->platform);
        switch ($opt) {
            case 'forget':
                if (!$srv->ifEmailExists($email)) {
                    $this->apiFailure(self::ERR_ACCOUNT_NOT_EXISTS);
                }
                if ($srv->sendResetEmail($email)) {
                    $this->apiSuccess($email);
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ["Failed to send reset email"]);
                }
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, ["Illegal option"]);
                break;
        }
    }

}