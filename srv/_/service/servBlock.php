<?php


namespace _;


use Core\unitInstance;

class servBlock extends serv_
{
    use unitInstance;

    const TYPE_MAP = [
        dataBlock::TYPE_DEFAULT => 'default',
        dataBlock::TYPE_HOME => 'home',
    ];

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function list($type=null)
    {
        $where = [
            'weight>0'
        ];
        if ($type) {
            $where['i_type'] = array_search($type, self::TYPE_MAP);
        }
        $res = dataBlock::sole($this->platform)->fetchAll($where, '*', null, null, "order by weight desc, id desc");
        foreach ($res as &$row) {
            $row['type'] = self::TYPE_MAP[$row['i_type']];
            $row['extra'] = json_decode($row['extra'], true);
            if (isset($row['extra']['list'])) {
                foreach ($row['extra']['list'] as $sn) {
                    $row['extra']['name'][$sn] = servLessonHub::sole($this->platform)->profile($sn)['title'];
                }
            }
        }
        return $res;
    }


}