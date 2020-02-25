<?php

namespace _;

use Core\library\Mysql;
use Core\library\Redis;

class data_
{
    const TOWARD_PREV = 'prev'; // 向前，不包括自身
    const TOWARD_NEXT = 'next'; // 向后，不包括自身
    const TOWARD_SELF = 'self'; // 自身
    const TOWARD_FORE = 'fore'; // 前部，包括自身，self+next
    const TOWARD_HIND = 'hind'; // 后部，包括自身, prev+self
    const TOWARD_NEAR = 'near'; // 附近，prev+fore

    const SN_USER = 'U';
    const SN_LESSON = 'L';
    const SN_ORDER = 'O';
    const SN_UNION_ORDER = 'UO';
    const SN_REFUND = 'R';
    const SN_DRAWCASH = 'D';
    const SN_PROMOTE = 'P';
    const SN_SERIES = 'S';
    const SN_ACTIVITY = 'A';
    const SN_ZSXQ = 'ZSXQ';
    const SN_BLOG = 'B';

    protected $platform;

    public function __construct($platform=null)
    {
        $this->platform = $platform;
    }

    /**
     * @param string $index
     * @return Mysql|mixed
     */
    public static function mysql($index = 'yike')
    {
        if (BOOT_MODE == BOOT_MODE_TEST) {
            $index .= "_test";
        }
        return Mysql::inst($index, __NAMESPACE__);
    }

    public static function redis($index = 'yike')
    {
        return Redis::inst($index, __NAMESPACE__);
    }

    public function uniqueSN($type)
    {
        return uniqid($type);
    }
}