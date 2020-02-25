<?php


namespace Admin;


use _\dataLessonBoard;
use Core\library\Mysql;
use Core\unitInstance;

class servLessonBoard extends \_\servLessonBoard
{
    use unitInstance;

    const TYPE_MAP = [
        dataLessonBoard::TYPE_ARGUE => 'argue',
        dataLessonBoard::TYPE_LIKE => 'like',
        dataLessonBoard::TYPE_DELETE => 'delete',
        dataLessonBoard::TYPE_TIPOFF => 'tipoff',
        dataLessonBoard::TYPE_HIDDEN => 'hidden',

    ];
    protected $data;

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
        $this->data = dataLessonBoard::sole($this->platform);
    }


    public function queryList(unitQueryBoard $query)
    {

        $dateField = $query->dateField == 'create' ? 'tms_create' : 'tms_update';
        $where = [
            "$dateField between ? and ?" => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"]
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
        if ($query->type) {
            $iType = array_search($query->type, servLessonBoard::TYPE_MAP);
            if ($iType !== false) {
                $where['i_type'] = $iType;
            }
        }
        if ($query->paySn) {
            $where['pay_sn'] = $query->paySn;
        }
        $list = dataLessonBoard::sole($this->platform)->fetchAll($where, '*');
        foreach ($list as &$item) {
            $item['message'] = json_decode($item['message'],true);
            $item['stats'] = json_decode($item['stats'],true);
            $item['uname'] = dataUser::sole($this->platform)->fetchOne(['id'=>$item['uid']],['name'],'name');
            $item['count_id'] = dataLessonBoard::mysql()->run("SELECT COUNT(*) as count from lesson_board where _id={$item['id']}")->fetch('count');
            if($item['i_type'] < dataLessonBoard::TYPE_LIKE) {
                $item['isDelete'] = true;
            } else {
                $item['isDelete'] = false;
            }

            $item['lesson'] = dataLesson::sole($this->platform)->fetchOne(['id'=>$item['lesson_id']],['title','sn','tuid']);

        }
        return $list;
    }


}