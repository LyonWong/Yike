<?php


namespace _\cli;


use _\dataUser;
use _\servTIM;

class ctrlUser extends ctrl_
{

    public function _DO_create()
    {
        $account = \input::cli('account')->value(true);
        $password = \input::cli('password')->value(true);
        $name = \input::cli('name', $account)->value();
    }

    public function _DO_creates($n)
    {
        $daoUser = dataUser::sole($this->platform);
        $TIM = servTIM::sole($this->platform);
        $tim = $TIM->tim();
        while ($n--) {
            $name = uniqid('ROB_');
            $uid = $daoUser->append(0, $name);
            $usn = $daoUser->fetchOne(['id'=>$uid], ['sn'], 0);
            $res = $tim->account_import($usn, $name, '');
            \output::debug('temp', "#$n. create user $usn, tim:".json_encode($res));
        }
    }

    public function _POST_bind($opt)
    {
        switch ($opt) {
            case 'telephone':
        }
    }

}