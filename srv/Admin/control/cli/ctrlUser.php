<?php


namespace Admin\cli;


use Admin\sign\servEmail;
use Admin\dataUser;
use Admin\servSign;
use Admin\servUser;

class ctrlUser extends ctrl_
{
    public function _DO_create()
    {
        $platform = \input::cli('platform')->value();
        $iRole = \input::cli('iRole', dataUser::ROLE_ADMIN)->toInt();
        $email = \input::cli('email')->value(true);
        $password = \input::cli('password')->value(true);

        $uid = servEmail::sole($platform)->create($email, $password);
        dataUser::updateByUid($uid, 'i_role', $iRole);

        if ($uid) {
            echo "Create user, uid=$uid";
        } else {
            echo "Create user failed.";
        }
    }

    public function _DO_prepare()
    {
        $email = \input::cli('email')->value(true);
        $res = servEmail::prepare($email);
        var_dump($res);
    }

    public function _DO_reset()
    {
        $account = \input::cli('account')->value(true);
        $password = \input::cli('password')->value(true);

        servSign::resetPassword($account,$password);
        //todo reset之后，原有的会话依然是有效的
    }

    public function _DO_scope()
    {
        $uid = \input::cli('uid')->value(true);
        $field = \input::cli('field')->value(true);
        $point = \input::cli('point')->value(true);
        $priv = \input::cli('priv')->toInt();
        servUser::sole(null)->setScopeAuth($uid, $field, $point, $priv);
    }

    public function _DO_group()
    {
        $uid = \input::cli('uid')->value(true);
        $groups = \input::cli('groups')->value(true);
        servUser::sole()->setScopeGroup($uid, $groups);
    }

}