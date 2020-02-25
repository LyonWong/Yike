<?php


namespace Admin\stats;


use Core\unitInstance;

class servGlobal extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }



}