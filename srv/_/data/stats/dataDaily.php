<?php


namespace _\stats;


use _\dataSole;
use Core\unitInstance;

class dataDaily extends dataSole
{
    use unitInstance;

    const TABLE_PREFIX = 'stats_daily_';

    /**
     * @param $platform
     * @param $date
     * @return dataDaily
     */
    public static function sole($platform, $date)
    {
        return self::_singleton($platform, $date);
    }

    public function __construct($platform, $date)
    {
        parent::__construct($platform);
        $date = strToDate($date, 'Ymd');
        $this->TABLE = self::TABLE_PREFIX.$date;
        $template = dataTimely::TABLE;
        $this->mysql->run("create table if not exists $this->TABLE like $template");
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
            $res = $this->fetchAll($where, ['idx', 'val'], 'idx');
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