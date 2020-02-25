<?php


namespace _;


use Core\unitInstance;

class dataRating extends dataSole
{
    use unitInstance;

    const TABLE = 'rating';

    const TOWAED_NEXT = '>';
    const TOWARD_PREVIOUS = '<';

    const STATUS_HIDDEN = -2;
    const STATUS_NORMAL = 0;
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


    public function append($lessonId, $tuid, $uid, $score, $remark, $iPark = 1)
    {
        $data = [
            'lesson_id' => $lessonId,
            'tuid' => $tuid,
            'suid' => $uid,
            'score' => $score,
            'remark' => $remark,
            'i_part' => $iPark
        ];
        do {
            $this->insert($data);
            $id = (int)$this->mysql->lastInsertId();
        } while (!$id);
        return $id;
    }

    public function getList($start = 0, $offset = 10, $toward, $pageFilter = [], $pageField = '*', $order = null)
    {
        if($start) {
            $pageFilter[] = "id $toward $start";
        }
        return $pages = $this->mysql->select($this->TABLE, $pageField, $pageFilter, "$order limit $offset")->fetchAll();
    }

    public function getCount($lessonId)
    {
        return $this->mysql->s("select round(avg(score),1) as avg_score,count(*) as rated_count from $this->TABLE")->w(['lesson_id' => $lessonId,'i_status >='.dataRating::STATUS_NORMAL])->e()->fetch();
    }

}