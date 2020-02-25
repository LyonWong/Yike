<?php


namespace Student;


use Core\unitInstance;

class dataTeacher extends \_\dataTeacher
{
    use unitInstance;

    const TABLE = 'teacher';

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

}