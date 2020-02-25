<?php


namespace _;


use Core\unitInstance;

class dataBlock extends dataSole
{
    use unitInstance;

    const TABLE = 'block';

    const TYPE_DEFAULT = 0; // 默认
    const TYPE_HOME = 1; // 首页

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }



}