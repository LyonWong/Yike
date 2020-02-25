<?php

namespace Core\library;


class Redis extends \Redis
{

    protected static $singletions = [];
    protected $index;

    public static function inst($index, $space=SPACE)
    {
        if (isset (self::$singletions[$index]) && self::$singletions[$index] instanceof self) {
            $inst = self::$singletions[$index];
        } else {
            $config = \config::load('redis', $index, null, null, $space);
            $host = $config['host'] ?? '127.0.0.1';
            $port = $config['port'] ?? 6379;
            $timeout = $config['timeout'] ?? 0.0;
            $inst = new self;
            $inst->connect($host, $port, $timeout);
            $inst->select($config['database'] ?? 0);
            $inst->index = $index;
        }
        return $inst;
    }


}