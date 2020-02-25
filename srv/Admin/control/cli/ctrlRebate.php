<?php


namespace Admin\cli;


use Admin\servRebate;
use Admin\servUser;

class ctrlRebate extends ctrl_
{
    public function _DO_single()
    {
        $usn = \input::cli('usn')->value(true);
        $amount = \input::cli('amount')->value(true);
        $info = \input::cli('info', '')->value();
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        if (!$uid) {
            exit("Illegal user");
        }
        $check = servRebate::sole($this->platform)->single($uid, $amount, $info);
        echo "rebate: " . ($check ? 'success' : 'failed') . LF;
    }


}