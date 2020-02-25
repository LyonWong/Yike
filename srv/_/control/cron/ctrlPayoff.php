<?php


namespace _\cron;


use _\servPayoff;
use Core\library\Tool;

class ctrlPayoff extends ctrl_
{

    public function _DO_hourly()
    {
        $hour = \input::cli('date', '-72 hours')->toDate('Y-m-d H:00:00');
        $stats = servPayoff::sole(null)->settlement($hour);
        $this->runCheck(http_build_query($stats, '#', ','));
    }

    public function _DO_duration()
    {
        $tmsStart = \input::cli('tmsStart', '-4 days')->toDate('Y-m-d H:i:s');
        $tmsEnd = \input::cli('tmsEnd', '-3 days')->toDate('Y-m-d H:i:s');
        $tmsSeries = Tool::timeSeries($tmsStart, $tmsEnd, 'Y-m-d H:00:00', SECONDS_HOUR);
        foreach ($tmsSeries as $tms) {
            $stats = servPayoff::sole(null)->settlement($tms);
            $this->runCheck($tms . '|' . http_build_query($stats, '#', ','));
        }
    }

}