<?php


namespace Admin;

use _\dataMoney;
use Core\unitInstance;
use Core\library\Mysql;

class servMoney extends \_\servMoney
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
        $this->data = dataMoney::sole($this->platform);
    }

    public function queryList(unitQueryUser $query)
    {
        $where = [];
        if ($query->userValue) {
            if ($query->userField == 'uid') {
                $where['id'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['sn'] = $query->userValue;
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["id in ($made[clause])"] = $made['params'];
            }
        }

        $list = dataUser::sole($this->platform)->fetchAll($where, 'id,sn,name,origin_id,tms_create');
        foreach ($list as &$item) {
            $item['balance'] = $this->balance($item['id']);
            $item['income'] = dataMoney::sole($this->platform)->sumAmount($item['id'], 'income');
            $item['expend'] = dataMoney::sole($this->platform)->sumAmount($item['id'], 'expend');

        }
        return $list;
    }

    public function countList($uid)
    {
        $money['balance'] = $this->balance($uid);
        $money['income'] = dataMoney::sole($this->platform)->sumAmount($uid, 1);
        $money['expend'] = dataMoney::sole($this->platform)->sumAmount($uid, -1);
        return $money;
    }

    public function queryDetailList(unitQueryMoney $query)
    {
        $where = [
            $query->dateField . " between ? and ?" => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"]
        ];
        $where['uid'] = $query->uid;

        if ($query->item) {
            $iStatus = array_search($query->item, servMoney::ITEM_MAP);
            if ($iStatus !== false) {
                $where['i_item'] = $iStatus;
            }
        }
        $list = dataMoney::sole($this->platform)->fetchAll($where, '*');
        foreach ($list as &$item) {
            $item['amount'] /= 100;
            $item['balance'] /= 100;
            $item['args'] = json_decode($item['args'], true);
            if (isset($item['args']['order_id'])) {

            }
        }
        return $list;


    }
}