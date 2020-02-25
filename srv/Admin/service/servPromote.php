<?php


namespace Admin;


use _\dataLessonAccess;
use _\dataPromote;
use Core\library\Mysql;
use Core\unitInstance_;

class servPromote extends \_\servPromote
{
    use unitInstance_;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function page(unitQueryPromote $query, $pageNum, $pageStep)
    {
        if(!$query->userValue &&  !$query->psn &&  !$query->tsn) {
            $where = [
                'tms_create between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"],
            ];
        } else {
            $where = [];
        }
        if ($query->psn) {
            $where['sn'] = $query->psn;
        }
        if ($query->tsn) {
            if ($query->tsn[0] == data::SN_LESSON) {
                $where['lesson_id'] = servLesson::sole($this->platform)->sn2id($query->tsn);
            }
            if ($query->tsn[0] == data::SN_SERIES) {
                $where['series_id'] = servLessonSeries::sole($this->platform)->sn2id($query->tsn);
            }
        }
        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['uid'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $uid = servUser::sole($this->platform)->usn2uid($query->userValue);
                $where['sn'] = $uid;
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["uid in ($made[clause])"] = $made['params'];
            }
        }
        return dataPromote::sole($this->platform)->paging($pageNum, $pageStep, $where, '*', "id desc");
    }

    public function queryReceivedUids($psn)
    {
        $daoAcess = dataLessonAccess::sole($this->platform);
        $pinfo = $this->info($psn);
        $uids = $daoAcess->fetchAll([
            'lesson_id' => $pinfo['lesson_id'],
            'i_event' => dataLessonAccess::EVENT_RECEIVE,
            "args->'$.promote'=?"=>[$psn]
        ], "distinct(uid)", null, 0);
        return $uids;
    }

}