<?php


namespace Admin;


use _\dataOrigin;
use _\servOrigin;
use Core\library\Mysql;
use Core\unitInstance;

class servOrder extends \_\servOrder
{
    use unitInstance;

    protected $data;

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->data = dataOrder::sole($this->platform);
    }


    public function page(unitQueryOrder $query, $pageNum, $pageStep)
    {
        if ($query->paySn) {
            $where['pay_sn'] = $query->paySn;
        } else {
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
            if ($query->status) {
                $iStatus = array_search($query->status, servOrder::STATUS_MAP);
                if ($iStatus !== false) {
                    $where['i_status'] = $iStatus;
                }
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

    public function export(unitQueryOrder $query)
    {
        if ($query->paySn) {
            $where['pay_sn'] = $query->paySn;
        } else {
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
            if ($query->status) {
                $iStatus = array_search($query->status, servOrder::STATUS_MAP);
                if ($iStatus !== false) {
                    $where['i_status'] = $iStatus;
                }
            }
        }
        return dataOrder::sole($this->platform)->fetchAll($where, '*');
    }
}