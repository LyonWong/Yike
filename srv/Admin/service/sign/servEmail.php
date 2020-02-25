<?php


namespace Admin\sign;


use Admin\dataScopeUser;
use Admin\dataUserAuth;
use Admin\dataUser;
use Admin\serv_;
use Core\library\Tool;
use Core\library\Email;
use Admin\data;
use Core\unitInstance;

class servEmail extends serv_
{
    use unitInstance;

    const SIGN_TOKEN_PREFIX = 'SIGN_ADMIN_';

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function genToken($account)
    {
        $token = Tool::genSecret(32, Tool::STR_FORMAL);
        $redisKey = $this::SIGN_TOKEN_PREFIX.$token;
        data::redis()->set($redisKey, $account, 1800);
        return $token;
    }

    public function verToken($token)
    {
        $redisKey = self::SIGN_TOKEN_PREFIX.$token;
        return data::redis()->get($redisKey);
    }

    public function ifEmailExists($email):bool
    {
        $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_EMAIL, $email);
        return boolval($res);
    }

    public static function checkEmailFormat($email):bool
    {
        if (strlen($email) < 6) {
            return false;
        }
        if (preg_match('#[\w+\.]+@\w+\.\w+#', $email)) {
            return false;
        }
        return true;
    }

    public static function checkPasswordFormat($email):bool
    {
        if (strlen($email) < 6) {
            return false;
        }
        if (preg_match('#[^\d]#', $email) == 0) {
            return false;
        }
        return true;
    }

    public static function prepare($email)
    {
        $token = self::genToken($email); // use email as account
        $domain = \config::load('boot', 'public', 'domain');
        $link = "http://$domain/sign-up?token=$token";
        $mailer = Email::SMTP('noreply');
        $mailer->Subject = "Yike Sign-Up";
        $mailer->Body = \view::tpl('/sign/email-prepare')->with('link', $link)->res();
        $mailer->addAddress($email);
        $res = $mailer->send();
        return $res;
    }

    public function create($email, $password)
    {
        $uid = dataUser::sole($this->platform)->append(dataUser::ROLE_ADMIN, $email);
        if (!$uid) {
            \servException::halt('Failed to add new user');
        }
        $passhash = password_hash($password, PASSWORD_DEFAULT);
        if (!dataUserAuth::sole($this->platform)->append(dataUserAuth::TYPE_EMAIL, $uid, $email, $passhash)) {
            \servException::halt("Failed to set auth of `$email`");
        }
        dataScopeUser::insert([
            'uid' => $uid,
            'groups' => '[]',
            'auths' => '{}'
        ]);
        return $uid;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|int uid on success
     */
    public static function verify($email, $password)
    {
        $res = dataUserAuth::sole()->search(dataUserAuth::TYPE_EMAIL, $email);
        if (password_verify($password, $res['code'])) {
            return $res['uid'];
        } else {
            return false;
        }
    }
}