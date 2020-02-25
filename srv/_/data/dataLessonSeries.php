<?php


namespace _;


use Core\unitInstance;

class dataLessonSeries extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_series';

    const STATUS_SUBMIT = 0; // 提交申请
    const STATUS_DENIED = -1; // 审核拒绝
    const STATUS_OPENED = 1 ; // 审核通过
    const STATUS_HALTED = -3; // 下线停售

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

    public function append($uid, $name, unitIntroduce $introduce, unitLessonSeriesScheme $scheme, array $lessonIds = [])
    {
        $data = [
            'uid' => $uid,
            'name' => $name,
            'introduce' => $introduce->encode(),
            'scheme' => $scheme->encode(),
            'lesson_ids' => json_encode($lessonIds)
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_SERIES);
            $this->insert($data);
            $id = $this->mysql->lastInsertId();
        } while (!$id && --$try>0);
        return $data['sn'] ?? false;
    }

    public function modify($sn, $name, unitIntroduce $introduce, unitLessonSeriesScheme $scheme, array $lessonIds = [])
    {
        $data = [
            'name' => $name,
            'introduce' => $introduce->encode(),
            'scheme' => $scheme->encode(),
            'lesson_ids' => json_encode($lessonIds)
        ];
        return (bool)$this->update($data, ['sn' => $sn])->rowCount();
    }

    protected function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key) {
                case 'introduce':
                case 'scheme':
                case 'lesson_ids':
                case 'extra':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }

    public function list($where)
    {
        return $res = $this->mysql->select($this->TABLE,'*',$where,'order by id desc')->fetchAll();

    }

    public function searchByTitle($name,$fields = 'id')
    {
        $pattern = str_replace('*', '%', $name);
        return $this->fetchAll([
            'name like ?' => [$pattern]
        ], $fields, null, 0);
    }

    public function fetchBySNs(array $SNs, $fields='*', array $filter=[], $_=null)
    {
        if (empty($SNs)) {
            return [];
        }
        $res = $this->mysql::makeData($SNs, '?', ',');
        $where = array_merge([
            "sn in ($res[clause])" => $res['params']
        ], $filter);
        return $this->fetchAll($where, $fields, null, null, $_);
    }


}