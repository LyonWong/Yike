<?php


namespace _\cli;


use Core\unitDoAction;

class ctrl_ extends \_\ctrl_
{
    use unitDoAction;

    protected $platform;

    public function __construct()
    {
        parent::__construct();
        $this->platform = \input::cli('platform')->value();
    }

    public function runBefore()
    {
        if (BOOT_MODE !== BOOT_MODE_CLI)
        {
            \coreException::halt('Access denied!');
        }
    }

}