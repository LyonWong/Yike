<?php


namespace Admin\cli;


use Admin\servLesson;
use Admin\servUser;

class ctrlLesson extends ctrl_
{
    public function _DO_access()
    {
        $lessonSn = \input::cli('lsn')->value(true);
        $usn = \input::cli('usn')->value(true);
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        servLesson::sole($this->platform)->access($lessonSn, $uid);
    }

}