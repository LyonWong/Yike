<?php


namespace Admin\cli;


use Core\unitDoAction;

class ctrl_ extends \Admin\ctrl_
{
    use unitDoAction;

    protected $platform;

    public function runBefore()
    {
        if (BOOT_MODE !== BOOT_MODE_CLI)
        {
            \coreException::halt('Access denied!');
        }
        $this->platform = \input::cli('platform')->value();
    }
}