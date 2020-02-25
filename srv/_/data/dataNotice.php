<?php


namespace _;


use Core\unitInstance;

class dataNotice extends dataSole
{
    use unitInstance;

    const TABLE = 'notice';

    const TYPE_ENROLL = 1;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($iType, $uid, array $args)
    {
        $this->insert([
            'i_type' => $iType,
            'uid' => $uid,
            'args' => json_encode($args)
        ]);
        return $this->mysql->lastInsertId();
    }

}