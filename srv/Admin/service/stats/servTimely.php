<?php


namespace Admin\stats;


use _\stats\dataTimely;
use _\stats\servDom;
use _\stats\servIdx;
use Admin\dataLesson;
use Core\unitInstance;

class servTimely extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function all()
    {
        $res = dataTimely::sole($this->platform)->fetchAll(null, '*');
        foreach ($res as &$item) {
            $item['idx'] = servIdx::pos2key($item['idx']);
        }
        return $res;
    }

    public function summary()
    {
        $res = dataTimely::sole($this->platform)->fetchAll(['dom'=>'*'], '*');
        $data = [];
        foreach ($res as $row) {
            $data[$row['idx']] = $row['val'];
        }
        return servIdx::boost($data);
    }

    public function lesson($uid=null)
    {
        $lessonIds = dataLesson::sole($this->platform)->fetchAll(
            $uid ? ['uid' => $uid] : null,
            'id',
            null,
            0
        );
        $doms = array_map(function($val){
            return servDom::build([
                servDom::ZONE_LESSON => $val
            ]);
        }, $lessonIds);
        $timely = dataTimely::sole($this->platform)->gets($doms);

        $stats = [];
        foreach ($timely as $item) {
            $dom = servDom::parse($item['dom']);
            $stats[$dom[servDom::ZONE_LESSON]][$item['idx']] = $item['val'];
        }
        krsort($stats);
        foreach ($stats as &$data) {
            $data = servIdx::boost($data);
        }
        return $stats;
    }



}