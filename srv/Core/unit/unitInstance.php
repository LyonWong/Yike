<?php

namespace Core;

trait unitInstance
{
    protected static $instances = [];


    use unitInstance_;

    public static function instances()
    {
        return self::$instances;
    }

}