<?php


namespace Teacher;


use Core\unitInstance;

class servTeacher extends \_\servTeacher
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