<?php


namespace _;


use Core\unitInstance;

class dataRelation extends dataSole
{
    use unitInstance;
    const TABLE = 'relation';

    const TYPE_FOLLOW = 1; //关注


    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform = null)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($uid, $_uid, $iType = 1,$score = 1): string
    {
        $data = [
            'uid' => $uid,
            '_uid' => $_uid,
            'i_type' => $iType,
            'score' => $score
        ];
        do {
            self::mysql()->insert(self::TABLE, $data);
            $id = (int)self::mysql()->lastInsertId();
        } while (!$id);
        return $id;
    }

    public function getList($start = 0, $offset = 10,  $pageFilter = [], $pageField = '*', $order = null)
    {
        if($start) {
            $pageFilter[] = "id > $start";
        }
        return $pages = $this->mysql->select($this->TABLE, $pageField, $pageFilter, "$order limit $offset")->fetchAll();
    }

}