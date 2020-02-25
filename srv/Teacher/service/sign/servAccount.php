<?php


namespace Teacher\sign;


use Core\library\Email;
use Core\library\Tool;
use Core\unitInstance;
use Teacher\data;
use Teacher\dataUserAuth;
use Teacher\dataUser;
use Teacher\serv_;

class servAccount extends serv_
{
    use unitInstance;

    const PASSWORD_RESET_TOKEN_PREFIX = 'PASSWORD_RESET_';

    /**
     * @param null $platform
     * @return self
     */
    public static function inst($platform=null)
    {
        return self::_singleton($platform);
    }

    public function ifEmailExists($email): bool
    {
        $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_EMAIL, $email);
        if (!$res) {
            $_res = dataUser::sole($this->platform)->fetchOne(["info->'$.email'=?"=>[$email]], "id, json_unquote(info->'$.email') as email");
            if ($_res) {
                dataUserAuth::sole($this->platform)->append(dataUserAuth::TYPE_EMAIL, $_res['id'], $_res['email'], '');
                $res = true;
            }
        }
        return boolval($res);
    }

    public function checkEmailFormat($email): bool
    {
        if (strlen($email) < 6) {
            return false;
        }
        if (preg_match('#[\w+\.]+@\w+\.\w+#', $email)) {
            return false;
        }
        return true;
    }

    public function sendResetEmail($email)
    {
        $token = Tool::genSecret(32, Tool::STR_FORMAL);
        $rkey = self::PASSWORD_RESET_TOKEN_PREFIX.$token;
        data::redis()->set($rkey, $email, SECONDS_HOUR);
        $mailer = Email::SMTP('noreply');
        $domain = \config::load('boot', 'public', 'domain', null, 'Teacher');
        $link = "$_SERVER[REQUEST_SCHEME]://$domain/sign-reset?token=$token";
        $mailer->Subject = "易灵微课密码重置";
        $mailer->Body =
            \view::tpl('/sign/email-reset')
                ->with('link', $link)
                ->with('domain', $domain)
                ->res();
        $mailer->addAddress($email);
        return $mailer->send();
    }

    public function checkResetToken($token)
    {
        $rkey = self::PASSWORD_RESET_TOKEN_PREFIX.$token;
        return  data::redis()->get($rkey);
    }

    public function checkPasswordFormat($password): bool
    {
        if (strlen($password) < 6) {
            return false;
        }
        if (preg_match('#[^\d]#', $password) == 0) {
            return false;
        }
        return true;
    }

    public function create($email, $password)
    {
        $uid = dataUser::sole($this->platform)->append(dataUser::ROLE_TEACHER, $email);
        if (!$uid) {
            \servException::halt('Failed to add new user');
        }
        $passhash = password_hash($password, PASSWORD_DEFAULT);
        if (!dataUserAuth::sole($this->platform)->insert([
            'i_type' => dataUserAuth::TYPE_EMAIL,
            'uid' => $uid,
            'uaid' => $email,
            'code' => $passhash])) {
            \servException::halt("Failed to set auth of `$email`");
        }
        return $uid;
    }

    public function reset($email, $password, $token)
    {
        $_email_ = $this->checkResetToken($token);
        if (!$_email_ || $email != $_email_) {
            return false;
        }
        $rkey = self::PASSWORD_RESET_TOKEN_PREFIX.$token;
        data::redis()->del($rkey);
        $passhash = password_hash($password, PASSWORD_DEFAULT);
        $res = dataUserAuth::sole($this->platform)->updateByUaid(dataUserAuth::TYPE_EMAIL, $email, 'code', $passhash);
        return boolval($res);
    }

    /**
     * @param $email
     * @param $password
     * @return bool|int uid on success
     */
    public function verify($email, $password)
    {
        $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_EMAIL, $email);
        if (password_verify($password, $res['code'])) {
            return $res['uid'];
        } else {
            return false;
        }
    }
}