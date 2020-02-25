<?php


namespace _\sign;


use Core\unitInstance;

class serv extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

}