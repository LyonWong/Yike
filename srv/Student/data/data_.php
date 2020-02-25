<?php

namespace Student;

use Core\library\Mysql;
use Core\library\Redis;

class data_
{
    const TOWARD_PREV = 'prev'; // 向前，不包括自身
    const TOWARD_NEXT = 'next'; // 向后，不包括自身
    const TOWARD_SELF = 'self'; // 自身
    const TOWARD_FORE = 'fore'; // 向前，包括自身
    const TOWARD_HIND = 'hind'; // 向后，包括自身

    protected $platform;

    public function __construct()
    {
    }

    /**
     * @param string $index
     * @return Mysql|mixed
     */
    public static function mysql($index = 'yike')
    {
        return Mysql::inst($index);
    }

    public static function redis($index = 'yike')
    {
        return Redis::inst($index);
    }
}