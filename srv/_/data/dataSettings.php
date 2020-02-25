<?php


namespace _;


use Core\unitInstance;

class dataSettings extends dataSole
{
    use unitInstance;

    const TABLE = 'settings';

    const TYPE_MP_REPLY = 1;
    const TYPE_LESSON_TAG = 2;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($data)
    {
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }

    public function match($iType, $pattern)
    {
        $res = $this->mysql->run("select * from `$this->TABLE` where `i_type`=? and `item` REGEXP ? order by id desc limit 1"
            , [$iType, $pattern]
        )->fetch();
        return $res;
    }

}