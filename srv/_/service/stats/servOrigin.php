<?php


namespace _\stats;


use _\serv_;
use Core\unitInstance;

class servOrigin extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function subs($lessonSn, $preId)
    {
        $srvOrigin = \_\servOrigin::sole($this->platform);
        $srvLesson = \_\servLesson::sole($this->platform);
        $lessonId = $srvLesson->sn2id($lessonSn);
        $dom = servDom::build([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => ''
        ]);
        $res = dataTimely::sole($this->platform)->fetchAll([
            "`dom` like ?" => [$dom.'%']
        ], '*');
        $data = [];
        foreach ($res as $row) {
            $_dom = servDom::parse($row['dom']);
            $idx = $row['idx'];
            $_oid = $srvOrigin->next(['id'=>$_dom[servDom::ZONE_ORIGIN]], $preId);
            if (!$_oid) {
                continue;
            }
            $data[$_oid][$idx] = $row['val'];
        }
        return $data;
    }

}