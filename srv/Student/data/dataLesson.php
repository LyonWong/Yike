<?php


namespace Student;


use Core\unitInstance_;

class dataLesson extends \_\dataLesson
{
    use unitInstance_;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function list($where, $fields)
    {
        return $this->mysql->select($this->TABLE, $fields, $where, 'order by tms_update,id desc')->fetchAll();
    }
}