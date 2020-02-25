<?php


namespace _;


class servAdaptor extends serv_
{
    const CLIENT_WXM = 'WXM'; // 微信移动端
    const CLIENT_WXA = 'WXA'; // 微信小程序

    public static function client($userAgent)
    {
        $patterns = [
            self::CLIENT_WXA => '/MicroMessenger.*miniProgram/i',
            self::CLIENT_WXM => '/MicroMessenger/i'
        ];
        foreach ($patterns as $client => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $client;
            }
        }
        return false;
    }

}