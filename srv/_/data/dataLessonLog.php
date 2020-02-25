<?php


namespace _;



class dataLessonLog extends dataSole
{

    public function append($lessonId, $uid, $iEvent, $args=[])
    {
        $data = [
            'lesson_id' => $lessonId,
            'uid' => $uid,
            'i_event' => $iEvent,
            'args' => json_encode($args)
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }

    public function events($lessonId=null, $uid=null, array $iEvents=null)
    {
        if ($iEvents) {
            $e = $this->mysql->makeData($iEvents, '?', ',');
            $where = [
                "i_event in ($e[clause])" => $e['params']
            ];
        } else {
            $where = [];
        }
        if ($lessonId) {
            $where['lesson_id'] = $lessonId;
        }
        if ($uid) {
            $where['uid'] = $uid;
        }

        $res = $this->mysql->select($this->TABLE, '*', $where, 'order by id')->fetchAll();
        foreach ($res as &$row) {
            $row['args'] = json_decode($row['args'], true);
        }
        return $res;
    }

}