<?php


namespace _\_;


use _\config;
use Core\library\Tool;
use Core\unitAPI;
use Core\unitDoAction;

class ctrl_ extends \_\ctrl_
{
    use unitDoAction;
    use unitAPI;

    const ERR_NOT_IN_ALLOWED_LIST = [1, '`%s` not in allowed list'];
    const ERR_ILLEGAL_SECRET = [2, '`%s` is not illegal secret'];

    public function runBefore()
    {
        $prev = parent::runBefore();
        $allowedIPs = config::load('option', 'allowed', 'internal.IPs');
        $ip = \input::ip();
        if (Tool::IPcheck($ip, $allowedIPs) == false) {
            $this->apiFailure(self::ERR_NOT_IN_ALLOWED_LIST, [$ip]);
        }
        return $prev;
    }

}