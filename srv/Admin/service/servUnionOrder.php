<?php


namespace Admin;


use _\dataOrigin;
use _\dataUnionOrder;
use _\servOrigin;
use Core\library\Mysql;
use Core\unitInstance;

class servUnionOrder extends \_\servUnionOrder
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
        $this->data = dataUnionOrder::sole($this->platform);
    }


    public function page(unitQueryUnionOrder $query, $pageNum, $pageStep)
    {
        if ($query->paySn) {
            $where['pay_sn'] = $query->paySn;
        } elseif ($query->unionOrderSn) {
            $where['sn'] = $query->unionOrderSn;
        }else
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
            if ($query->status) {
                $iStatus = array_search($query->status, servOrder::STATUS_MAP);
                if ($iStatus !== false) {
                    $where['i_status'] = $iStatus;
                }
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
//        return $this->data->paging($pageNum, $pageStep, $where, ['id','sn','i_type','uid','origin_id','order_amount','paid_amount','i_pay_way','pay_sn','i_status','tms_create'], 'id desc');
    }

    public function export(unitQueryUnionOrder $query)
    {
        if ($query->paySn) {
            $where['pay_sn'] = $query->paySn;
        } elseif ($query->unionOrderSn) {
            $where['sn'] = $query->unionOrderSn;
        }else
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
            if ($query->status) {
                $iStatus = array_search($query->status, servOrder::STATUS_MAP);
                if ($iStatus !== false) {
                    $where['i_status'] = $iStatus;
                }
            }
        }
        return dataUnionOrder::sole($this->platform)->fetchAll($where, '*');
    }

    public function detail($unionOrderSn)
    {
        $ret = dataUnionOrder::sole($this->platform)->fetchOne(['sn'=>$unionOrderSn],'*');
        $extra = json_decode($ret['extra'],true);
        $ret['order_set'] = json_decode($ret['order_set'],true);
        $extra['cost']['total'] = $extra['cost']['total'] / 100;//全系列单课总价(勾选)
        $extra['cost']['series'] = $extra['cost']['series'] / 100;//全系列打包价
        $extra['cost']['prime'] = $extra['cost']['prime'] / 100;//订单单课总价
        $extra['cost']['order'] = $extra['cost']['order'] / 100;//订单加权价格
        $extra['cost']['deduct'] = $extra['cost']['deduct'] / 100;//优惠抵扣价
        $series = $extra['series_sn'] ? servLessonSeries::sole($this->platform)->detail($extra['series_sn']) :'';
        json_encode($extra['lesson_ids']);
    }

}