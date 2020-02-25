<?php


namespace _;


use Core\unitInstance;

class dataLessonActivity extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_activity';

    const TYPE_REMIT = 1;

    const STATUS_INIT = 0;
    const STATUS_GOON = 1;
    const STATUS_DONE = 2;

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

    public function append($iType, $refer, $uid, array $args)
    {
        $data = [
            'i_type' => $iType,
            'refer' => $refer,
            'uid' => $uid,
            'args' => json_encode($args),
            'i_status' => self::STATUS_INIT,
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_ACTIVITY);
            $this->insert($data);
            $id = $this->mysql->lastInsertId();
        } while (!$id && --$try>0);
        return $data['sn'] ?? false;
    }

}