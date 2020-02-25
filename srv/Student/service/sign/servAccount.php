<?php


namespace Student\sign;


use Core\unitInstance;
use Teacher\dataUserAuth;
use Teacher\dataUser;
use Teacher\serv_;

class servAccount extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function ifEmailExists($email): bool
    {
        $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_EMAIL, $email);
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

    public function checkPasswordFormat($email): bool
    {
        if (strlen($email) < 6) {
            return false;
        }
        if (preg_match('#[^\d]#', $email) == 0) {
            return false;
        }
        return true;
    }

    public function create($email, $password)
    {
        $uid = dataUser::sole($this->platform)->append(0, $email);
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