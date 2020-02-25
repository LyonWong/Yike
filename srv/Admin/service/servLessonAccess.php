<?php


namespace Admin;

use _\dataLessonAccess;
use Core\unitInstance;
use Core\library\Mysql;


class servLessonAccess extends serv_
{
    use unitInstance;

    protected $data;

    const ACCESS_MAP = [
        dataLessonAccess::EVENT_BROWSE => 'browse',
        dataLessonAccess::EVENT_ENROLL => 'enroll',
        dataLessonAccess::EVENT_ACCESS => 'access',
        dataLessonAccess::EVENT_CONFIRM => 'confirm',
        dataLessonAccess::EVENT_LEAVE => 'leave',
        dataLessonAccess::EVENT_RESET => 'reset',
        dataLessonAccess::EVENT_CANCEL => 'cancel',
        dataLessonAccess::EVENT_REFUND => 'refund',
        dataLessonAccess::EVENT_RECEIVE => 'receive',
        dataLessonAccess::EVENT_REMIND =>'remind'

    ];

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->data = dataLessonAccess::sole($this->platform);
    }


    public function page(unitLessonAccessQuery $query, $pageNum, $pageStep)
    {
        $where = [
            'tms between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"]
        ];
        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['uid'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['uid'] = servUser::sole($this->platform)->usn2uid($query->userValue);
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["uid in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->lessonValue) {
            if ($query->lessonField == 'id') {
                $where['lesson_id'] = $query->lessonValue;
            } elseif ($query->lessonField == 'sn') {
                $where['lesson_id'] = servLesson::sole($this->platform)->sn2id($query->lessonValue);
            } else {
                $lessonIds = dataLesson::sole($this->platform)->searchByTitle($query->lessonValue);
                $made = Mysql::makeData($lessonIds, '?',',');
                $where["lesson_id in ($made[clause])"] = $made['params'];
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

    public function reportOnLesson($lessonId, $uid)
    {
        $res = dataLessonAccess::sole($this->platform)->fetchAll(['lesson_id' => $lessonId, 'uid' => $uid], '*', 'i_event');
        return $res;
    }

}