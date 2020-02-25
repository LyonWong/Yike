<?php


namespace Admin;


use _\dataLessonRecord;
use _\dataLessonSeries;
use _\dataTicket;
use _\servTIM;
use Core\library\Mysql;
use Core\unitInstance;

class servLesson extends \_\servLesson
{
    use unitInstance;

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
        $this->data = dataLesson::sole($this->platform);
    }


    public function page(unitQueryLessonList $query, $pageNum, $pageStep)
    {

        if(!$query->userValue &&  !$query->seriesValue &&  !$query->lessonValue) {
            $where = [
                'tms_create between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"],
            ];
        } else {
            $where = [];
        }
        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['tuid'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['tuid'] = servUser::sole($this->platform)->usn2uid($query->userValue);
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["tuid in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->lessonValue) {
            if ($query->lessonField == 'id') {
                $where['id'] = $query->lessonValue;
            } elseif ($query->lessonField == 'sn') {
                $where['id'] = servLesson::sole($this->platform)->sn2id($query->lessonValue);
            } else {
                $lessonIds = dataLesson::sole($this->platform)->searchByTitle($query->lessonValue);
                $made = Mysql::makeData($lessonIds, '?',',');
                $where["id in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->seriesValue) {
            if ($query->seriesField == 'id') {
                $where['category'] = servLessonSeries::sole($this->platform)->id2sn($query->seriesValue);

            } elseif ($query->seriesField == 'sn') {
                $where['category'] = $query->seriesValue;
            } else {
                $seriesSns = dataLessonSeries::sole($this->platform)->searchByTitle($query->seriesValue,'sn');
                $made = Mysql::makeData($seriesSns, '?',',');
                $where["category in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->step) {
            $iStatus = array_search($query->step, servLesson::STEP_MAP);
            if ($iStatus !== false) {
                $where['i_step'] = $iStatus;
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

    public function map(unitLessonQuery $lessonFilter)
    {
        $where = $lessonFilter->toWhere();
        $res = $this->data->fetchAll($where, '*', 'id');
        foreach ($res as &$item) {
            $item['teacher'] = servUser::sole($this->platform)->uid2profile($item['tuid']);
            $item['price'] /= 100;
        }
        return $res;
    }

    public function reviewList()
    {
        $tickets = dataTicket::sole($this->platform)->todoList(dataTicket::TYPE_CREATE_LESSON);
        $list = [];
        foreach ($tickets as $ticket) {
            $list[] = [
                'id' => $ticket['id'],
                '_id' => $ticket['_id'],
                'lesson' => servLesson::sole($this->platform)->profile($ticket['content']['lesson_sn']),
                'tms_create' => $ticket['tms_create'],
            ];
        }
        return $list;
    }

    public function forbidSendMsg($lessonSn, $usn)
    {
        $room = self::sn2room($lessonSn);
        servTIM::sole($this->platform)->forbidSendMsg($room['teacher'], $usn, 3600 * 24 * 30);
        servTIM::sole($this->platform)->forbidSendMsg($room['discuss'], $usn, 3600 * 24 * 30);
    }

    public function deleteGroupMember($lessonSn, $usn)
    {
        $room = self::sn2room($lessonSn);
        servTIM::sole($this->platform)->deleteGroupMember($room['teacher'], $usn);
        servTIM::sole($this->platform)->deleteGroupMember($room['discuss'], $usn);
    }

    public function deleteRecord($cursor)
    {
        list($recordId) = explode('-', $cursor);
        $dao = dataLessonRecord::sole($this->platform);
        $rec['i_type'] = dataLessonRecord::TYPE_DELETE;
        return $dao->update($rec, ['id' => $recordId]);
    }

}