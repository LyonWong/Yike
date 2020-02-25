<?php


namespace Admin\cron;


use Core\unitDoAction;

class ctrl_ extends \Admin\ctrl_
{
    use unitDoAction;

    protected $_tms;
    protected $_tme;
    protected $_now;
    protected $_runCheckes = [];

    public function runBefore()
    {
        $pres = parent::runBefore();
        $this->_now = time();
        return $pres;
    }

    public function runBehind()
    {
        $this->runCheck('Done');
        $content = $this->_WAY_."\n";
        foreach ($this->_runCheckes as $check) {
            $content .= "+$check[time](s)\t Mem: $check[memory](M)\t Avg: $check[avgload]\t Msg: $check[message]\n";
        }
        \output::debug('cron', $content);
        $pres = parent::runBehind();
        return $pres;
    }

    protected function runCheck($message)
    {
        $now = time();
        $time = $now - $this->_now;
        $this->_now = $now;

        $memory = memory_get_peak_usage(true) / (1<<20);
        $avgload = implode(',', sys_getloadavg());
        $this->_runCheckes[] = [
            'time' => $time,
            'memory' => $memory,
            'avgload' => $avgload,
            'message' => $message
        ];
    }

}