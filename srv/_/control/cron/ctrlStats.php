<?php


namespace _\cron;



use _\stats\servCron;

class ctrlStats extends ctrl_
{
    public function _DO_daily()
    {
        $date = \input::cli('date', 'yesterday')->value();
        servCron::sole(null)->dailyShot($date);
    }

    public function _DO_clear()
    {
        $tables = servCron::sole(null)->clear();
        $this->runCheck("clear:".json_encode($tables));
    }

}