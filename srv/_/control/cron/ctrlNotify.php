<?php


namespace _\cron;


use _\servNotify;

class ctrlNotify extends ctrl_
{
    public function _DO_()
    {
    }

    public function _DO_lesson_prepare()
    {

    }

    public function _DO_daily_income()
    {
        $date = \input::cli('date', 'today')->toDate('Y-m-d');
        $tmsStart = date('Y-m-d 22:00:00', strtotime($date . '-1 day'));
        $tmsEnd = $date . ' 21:59:59';
        servNotify::sole(null)->incomeNotify($tmsStart, $tmsEnd);
    }

    //每天22点向学员发送
    public function _DO_daily_board2student()
    {
        $date = \input::cli('date', 'today')->toDate('Y-m-d');
        $tmsStart = date('Y-m-d 22:00:00', strtotime($date . '-1 day'));
        $tmsEnd = $date . ' 21:59:59';
        servNotify::sole(null)->board2student($tmsStart, $tmsEnd);
    }

    //每天22点向讲师发送
    public function _DO_daily_board2teacher()
    {
        $date = \input::cli('date', 'today')->toDate('Y-m-d');
        $tmsStart = date('Y-m-d 22:00:00', strtotime($date . '-1 day'));
        $tmsEnd = $date . ' 21:59:59';
        servNotify::sole(null)->board2teacher($tmsStart, $tmsEnd);
    }

    //每天22点向讲师发送
    public function _DO_daily_boardTeacher()
    {
        $date = \input::cli('date', 'today')->toDate('Y-m-d');
        $tmsStart = date('Y-m-d 22:00:00', strtotime($date . '-1 day'));
        $tmsEnd = $date . ' 21:59:59';
        servNotify::sole(null)->boardTeacherNotify($tmsStart, $tmsEnd);
    }
}