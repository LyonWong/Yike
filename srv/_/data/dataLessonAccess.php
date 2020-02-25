<?php
/**
 * 课程获取
 * 记录学员的报名、听课等日志
 */


namespace _;


use Core\unitInstance;

class dataLessonAccess extends dataLessonLog
{
    use unitInstance;

    const TABLE = 'lesson_access';

    const EVENT_BROWSE = 1; // 浏览，查看详情
    const EVENT_ENROLL = 2; // 预定，完成报名
    const EVENT_ACCESS = 3; // 使用，进入课堂
    const EVENT_CONFIRM = 4; // 确认，不能退款
    const EVENT_LEAVE = 5; // 离开课堂
    const EVENT_RECEIVE = 6; // 领取优惠券
    const EVENT_INVITED = 7; // 受邀，作为嘉宾
    const EVENT_REMIND = 8; // 未听课提醒
    const EVENT_AUDITION = 9; // 试听
    const EVENT_RESET = 0; // 重置
    const EVENT_CANCEL = -1; // 退出
    const EVENT_REFUND = -2; // 退款

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function lastEvents($uid)
    {
        $ids = $this->mysql->select($this->TABLE, "max(id), lesson_id", ['uid' => $uid], 'group by lesson_id')->fetchAll(null, 0);
        if (count($ids)) {
            $res = $this->mysql->s("select * from $this->TABLE")->v('where id in ', $ids)->e()->fetchAll();
            foreach ($res as &$item) {
                $item['args'] = json_decode($item['args'], true);
            }
        } else {
            $res = [];
        }
        return $res;
    }

    public function lastEvent($uid, array $events = [])
    {
        $res = $this->mysql->s("select * from `$this->TABLE`")->w(['uid' => $uid])->v("and i_event in ", $events)->a("order by id desc limit 1")->e()->fetch();
        return $res;
    }

    public function lastAccessEventsByLesson($lessonId): array
    {
        $ids = $this->mysql->select($this->TABLE, "max(id) as mid, uid", [
            'lesson_id' => $lessonId,'i_event not in('.dataLessonAccess::EVENT_RECEIVE.','.dataLessonAccess::EVENT_REMIND.','.dataLessonAccess::EVENT_INVITED.')'
        ], 'group by uid')->fetchAll(null, 'mid');
        if ($ids) {
            $res = $this->mysql->s("select * from $this->TABLE")->v("where id in ", $ids)->e()->fetchAll();
        } else {
            $res = [];
        }
        return $res;

    }

    public function lastAccessEventsByLessonUid($lessonId,$uid): array
    {
        $id = $this->mysql->select($this->TABLE, "max(id)", [
            'lesson_id' => $lessonId,'uid'=>$uid,'i_event not in('.dataLessonAccess::EVENT_RECEIVE.','.dataLessonAccess::EVENT_REMIND.','.dataLessonAccess::EVENT_INVITED.')'
        ])->fetch();
        if ($id) {
            $res = $this->mysql->s("select i_event from $this->TABLE")->v("where id = ", $id)->e()->fetchAll();
        } else {
            $res = [];
        }
        return $res;

    }

    public function lastEventOnLesson($lessonId, $uid, array $event=[]): array
    {
        $where = [
            'lesson_id' => $lessonId,
            'uid' => $uid,
        ];
        if ($event) {
            $m = $this->mysql::makeData($event, '?', ',');
            $where["i_event in ($m[clause])"] = $m['params'];
        }
        $res = $this->mysql->select($this->TABLE, '*', $where, "order by id desc limit 1")->fetch();
        if ($res) {
            $res['args'] = json_decode($res['args'], true);
        } else {
            $res = [];
        }
        return $res;
    }

    public function firstEventsOnLesson($lessonId, $uid): array
    {
        $res = $this->mysql->select($this->TABLE, '*', [
            'lesson_id' => $lessonId,
            'uid' => $uid,
        ], "order by id desc")->fetchAll('i_event');
        foreach ($res as &$item) {
            $item['args'] = json_decode($item['args'], true);
        }
        return array_reverse($res, true);
    }

    public function firstEventOnLesson($lessonId, $uid, array $event=[]):array
    {
        $where = [
            'lesson_id' => $lessonId,
            'uid' => $uid,
        ];
        if ($event) {
            $m = $this->mysql::makeData($event, '?', ',');
            $where["i_event in ($m[clause])"] = $m['params'];
        }
        $res = $this->mysql->select($this->TABLE, '*', $where, "order by id limit 1")->fetch();
        if ($res) {
            $res['args'] = json_decode($res['args'], true);
        } else {
            $res = [];
        }
        return $res;
    }

    public function lastLessonOfUser($uid): array
    {
        $res = $this->mysql->select($this->TABLE, '*', ['uid' => $uid, "i_event>0"], "order by id desc limit 1")->fetch();
        if ($res) {
            $res['args'] = json_decode($res['args'], true);
        } else {
            $res = [];
        }
        return $res;
    }

    public function count($lessonId)
    {
        $count = $this->fetchOne(['lesson_id' => $lessonId, 'i_event' => self::EVENT_ENROLL], 'count(distinct uid)', 0);
        return $count;
    }

    public function unique($lessonId, array $events)
    {
        $res = $this->mysql->s("select count(distinct uid) from $this->TABLE")
            ->w(['lesson_id' => $lessonId])
            ->v("and i_event in ", $events)
            ->e()
            ->fetch(0);
        return $res;
    }

    public function history($uid)
    {
        $ids = $this->mysql->select($this->TABLE, "max(id), lesson_id", ['uid' => $uid, "i_event <>" . self::EVENT_BROWSE, "i_event <>" . self::EVENT_RECEIVE], 'group by lesson_id')->fetchAll(null, 0);
        $res = $this->mysql->s("select * from $this->TABLE")->v('where id in ', $ids)->a('order by id desc')->e()->fetchAll();
        foreach ($res as &$item) {
            $item['args'] = json_decode($item['args'], true);
        }
        return $res;
    }

    public function tillEvent($lessonId, $iEvent)
    {
        $res = $this->fetchAll(['lesson_id' => $lessonId], '*', 'uid');
        foreach ($res as $i => $item) {
            if ($item['i_event'] != $iEvent) {
                unset($res[$i]);
            }
        }
        return $res;
    }

    public function slice($lessonId, int $limit, array $filter=[])
    {
        $filter['lesson_id'] = $lessonId;
        return $this->mysql->select($this->TABLE, '*', $filter, "order by id desc limit $limit")->fetchAll();
    }

    public function ownLessons($uid, $events, $filter=[])
    {
        $where = array_merge($filter, ['uid' => $uid]);
        $ids = $this->mysql->s("select max(id) as id, lesson_id from $this->TABLE")
            ->w($where)
            ->v("and i_event in ", $events)
            ->a("group by lesson_id")
            ->e()->fetchAll(null, 0);
        if (!count($ids)) {
            return [];
        }
        $res = $this->mysql->s("select * from $this->TABLE")
            ->w($where)
            ->v("and id in ", $ids)
            ->a('order by id desc')
            ->e()->fetchAll();
        foreach ($res as &$row) {
            $row['args'] = json_decode($row['args'], true);
        }
        return $res;
    }

}