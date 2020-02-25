<?php


namespace Admin;


use Core\unitInstance;
use Core\library\Mysql;

class servRefund extends \_\servRefund
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
        $this->data = dataRefund::sole($this->platform);
    }
    

    public function page(unitQueryRefund $query, $pageNum, $pageStep)
    {
        if ($query->orderValue) {
            $where = [];
            if ($query->orderField == 'id') {
                $where['order_id'] = $query->orderValue;
            } elseif ($query->orderField == 'sn') {
                $where['order_id'] = \_\servOrder::sole($this->platform)->sn2id($query->orderValue);
            }
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
                    $lessonId = $query->lessonValue;
                    $orderIds = dataOrder::sole($this->platform)->fetchAll(['lesson_id' => $lessonId], 'id', null, 0);
                } elseif ($query->lessonField == 'sn') {
                    $lessonId = servLesson::sole($this->platform)->sn2id($query->lessonValue);
                    $orderIds = dataOrder::sole($this->platform)->fetchAll(['lesson_id' => $lessonId], 'id', null, 0);
                } else {
                    $lessonIds = dataLesson::sole($this->platform)->searchByTitle($query->lessonValue);
                    $made = Mysql::makeData($lessonIds, '?', ',');
                    $orderWhere["lesson_id in ($made[clause])"] = $made['params'];
                    $orderIds = dataOrder::sole($this->platform)->fetchAll($orderWhere, 'id', null, 0
                    );
                }
                $made = Mysql::makeData($orderIds, '?', ',');
                $where["order_id in ($made[clause])"] = $made['params'];

            }
            if ($query->status) {
                $iStatus = array_search($query->status, servRefund::STATUS_MAP);
                if ($iStatus !== false) {
                    $where['i_status'] = $iStatus;
                }
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

}