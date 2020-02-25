<?php


namespace Admin;


use Core\library\Email;
use Core\library\Tool;

class servSign extends serv_
{
    const SIGN_TOKEN_PREFIX = 'SIGN_';

    public static function genToken($account)
    {
        $token = Tool::genSecret(32, Tool::STR_FORMAL);
        $redisKey = self::SIGN_TOKEN_PREFIX.$token;
        data::redis()->set($redisKey, $account, 1800);
        return $token;
    }

    public static function verToken($token)
    {
        $redisKey = self::SIGN_TOKEN_PREFIX.$token;
        return data::redis()->get($redisKey);
    }


    /**
     * 发送注册邀请邮件
     * @param $email
     * @return bool
     * @throws \coreException
     * @throws \phpmailerException
     */
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
        print_r($res);
//        if ($res && !dataUser::fetchOne(['name'=>$email], 'id', 0) && !dataUserAuth::search(dataUserAuth::TYPE_EMAIL, $email)) {
//            dataUser::append(dataUser::ROLE_ADMIN, $email);
//        }
        return $res;
    }

    /**
     * 确认邀请，完成注册
     * @param $email
     * @param $name
     * @param $password
     * @return bool
     */
    public static function confirm($email, $name, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (!$user = dataUser::fetchOne(['name'=>$email], 'id')) {
            return false;
        }
        dataUser::updateByUid($user['id'], ['name'=> $name, 'password'=>$hash, 'status' => dataUser::STATUS_NORMAL]);
        return $user['id'];
    }

    /**
     * 关联账户
     * @param $plat
     * @param $account
     * @return bool|\clsPDOStatement|mixed
     */
    public static function associate($plat, $account)
    {
        if ($user = dataUser::fetchMixed($account)) {
            return $user['id'];
        } else {
            return dataUser::append($account, ['plat' => $plat, 'status'=> dataUser::STATUS_ASSOC]);
        }
    }

    /**
     * 发送忘记密码邮件
     * @param $email
     * @return bool
     * @throws \coreException
     * @throws \phpmailerException
     */
    public static function forgot($email)
    {
        $token = self::genToken($email); // use email as account
        $domain = \config::load('boot', 'public', 'domain');
        $link = "http://$domain/sign-reset?token=$token";
        $mailer = Email::SMTP('noreply');
        $mailer->Subject = "EStats Sign-Forgot";
        $mailer->Body = \view::tpl('/sign/email-forgot')->with('link', $link)->res();
        $mailer->addAddress($email);
        $res = $mailer->send();
        return $res;
    }

    public static function changePassword($uid, $prePassword, $newPassword)
    {

    }

    /**
     * 充值密码
     * @param $account
     * @param $password
     * @return bool|mixed
     */
    public  static function resetPassword($account, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return dataUserAuth::sole()->updateByUaid(dataUserAuth::TYPE_EMAIL,$account,'code',$hash);
    }

    /**
     * 检查密码合法性
     * @param $password
     * @return bool
     */
    public static function checkPassword($password)
    {
        if (strlen($password) < 6) {
            return false;
        }
        if (preg_match('/[^\d]/', $password) == 0) {
            return false;
        }
        return true;
    }

    public static function checkAccount($account)
    {
        return dataUserAuth::sole()->search(dataUserAuth::TYPE_EMAIL,$account);
    }



}