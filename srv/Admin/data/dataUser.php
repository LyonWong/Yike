<?php


namespace Admin;


class dataUser extends \_\dataUser
{
    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }
}