<?php


namespace _;


use Core\unitInstance;

class dataTeacher extends dataSole
{
    use unitInstance;

    const TABLE = 'teacher';

    const STATUS_APPLYING = 1; // 申请中
    const STATUS_REJECTED = -1; // 申请驳回
    const STATUS_ACCEPTED = 2; // 已认证
    const STATUS_CREDIBLE = 3; // 可信任

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

    public function append($tuid, unitTeacherDatum $unitDatum)
    {
        $data = [
            'tuid' => $tuid,
            'datum' => json_encode([
                'about' => $unitDatum->about,
            ]),
            'i_status' => self::STATUS_CREDIBLE, // 临时将初始状态改为受信
            'rate_parts' => '{}'
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }
}