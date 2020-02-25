<?php


namespace _\stats;


use _\dataSole;
use Core\unitInstance;

class dataTimely extends dataSole
{
    use unitInstance;

    const TABLE = 'stats_timely';

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

    public function get($dom, int $idx = null)
    {
        $where = [
            'dom' => $dom,
        ];
        if ($idx) {
            $where['idx'] = $idx;
            $res = $this->fetchOne($where, 'val', 0);
        } else {
            $res = $this->fetchAll($where, ['idx', 'val'], 'idx', 'val');
        }
        return $res;
    }

    public function gets($doms)
    {
        return $this->mysql->s("SELECT * FROM $this->TABLE")->v("where dom in ", $doms)->e()->fetchAll();
    }

    public function set($dom, int $idx, int $val)
    {
        $this->insert([
            'dom' => $dom,
            'idx' => $idx,
            'val' => $val
        ], ['val']);
        return $this->mysql->lastInsertId();
    }

    public function var($dom, int $idx, int $val)
    {
        $this->insert([
            'dom' => $dom,
            'idx' => $idx,
            'val' => $val
        ], "`val`=`val`+'$val'");
    }

}