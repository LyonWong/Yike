<?php


namespace Teacher;


use _\dataLessonSeries;
use _\servOrigin;
use _\stats\dataTimely;
use _\stats\servDom;
use _\stats\servIdx;
use Core\unitInstance;

class servStats extends serv_
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

    public function overview($tuid)
    {
        $dom = servDom::build([
            servDom::ZONE_TEACHER => $tuid
        ]);
        $res = dataTimely::sole($this->platform)->get($dom);
        return servIdx::boost($res);
    }

    public function lesson($tuid)
    {
        $IDs = dataLesson::sole($this->platform)->fetchAll(['tuid' => $tuid], 'id', null, 0);
        $seriesSns = dataLessonSeries::sole($this->platform)->fetchAll(['uid'=>$tuid], 'sn', null, 0);
        foreach ($seriesSns as $seriesSn) {
             $IDs = array_merge ($IDs, dataLesson::sole($this->platform)->fetchAll(['category' => $seriesSn], 'id', null, 0));
        }
        $IDs = array_unique($IDs);
        $DOMs = [];
        foreach ($IDs as $id) {
            $DOMs[] = servDom::build([
                servDom::ZONE_LESSON => $id
            ]);
        }
        $res = dataTimely::sole($this->platform)->gets($DOMs);
        $data = [];
        foreach ($res as $row) {
            $dom = servDom::parse($row['dom']);
            $idx = $row['idx'];
            $lessonId = $dom[servDom::ZONE_LESSON];
            $data[$lessonId][$idx] = $row['val'];
        }
        $ret = [];
        foreach ($IDs as $id) {
            $ret[] = [
                'lesson' => servLesson::sole($this->platform)->profile($id, 'id'),
                'data' => servIdx::boost($data[$id]??[]),
            ];
        }
        return $ret;
    }

    public function origin($lessonSn, $preId)
    {
        $servLesson = servLesson::sole($this->platform);
        $servOrigin = servOrigin::sole($this->platform);
        $lesson = $servLesson->profile($lessonSn);
        $lessonId = $servLesson->sn2id($lessonSn);
        $dom = servDom::build([
            servDom::ZONE_LESSON => $lessonId
        ]);

        $total = [];
        $total['origin'] = $servOrigin->profile($preId);

        $subRes = dataTimely::sole($this->platform)->fetchAll([
            "`dom` like ?" => [$dom . '&%'],
        ], '*');
        $subs = [];
        foreach ($subRes as $row) {
            $dom = servDom::parse($row['dom']);
            $idx = $row['idx'];
            if ($dom[servDom::ZONE_ORIGIN] == $preId) {
                $total['data'][$idx] = ($total['data'][$idx] ?? 0 ) + $row['val'];
            }
            if ($dom[servDom::ZONE_ORIGIN] == '0') {
                $subs['0']['origin'] = [
                    'id' => -1,
                    'ids' => '',
                    'key' => '',
                    'name' => '其他'
                ];
                $subs['0']['data'][$idx] = $row['val'];
            }
            $oid = $servOrigin->next(['id'=>$dom[servDom::ZONE_ORIGIN]], $preId);
            if (!$oid) {
                continue;
            }
            if (empty($subs[$oid])) {
                $subs[$oid]['origin'] = $servOrigin->profile($oid);
            }
            $total['data'][$idx] = ($total['data'][$idx] ?? 0 ) + $row['val'];
            $subs[$oid]['data'][$idx] = ($subs[$oid]['data'][$idx] ?? 0) + $row['val'];
        }

        $total['data'] = servIdx::boost($total['data']);
        foreach ($subs as $oid => &$item) {
            $item['data'] = servIdx::boost($item['data']);
        }

        $tier = $servOrigin->tier($preId);
        $tier[0]['name'] = $lesson['title'];
        $ret = [
            'lesson' => $lesson,
            'tier' => $tier,
            'total' => $total,
            'subs' => array_values($subs),
        ];
        return $ret;
    }


}