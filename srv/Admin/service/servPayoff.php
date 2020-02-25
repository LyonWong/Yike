<?php


namespace Admin;


use _\dataPayoff;
use _\dataUserKeep;
use Core\unitInstance;

class servPayoff extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function list()
    {
        $rows = dataUserKeep::sole($this->platform)->fetchAll([
            'i_item' => dataUserKeep::ITEM_PAYOFF
        ], ['uid', 'obj']);
        $data = [];
        foreach ($rows as $row) {
            $row['obj'] = json_decode($row['obj'], true);
            $data[] = [
                'user' => servUser::sole($this->platform)->uid2profile($row['uid']),
                'gross' => $row['obj']['gross']/100,
                'remain' => $row['obj']['remain']/100
            ];
        }
        return $data;
    }

    public function query($uid, $orderId)
    {
        $where = array_filter([
            'uid' => $uid,
            'order_id' => $orderId
        ]);
        $res = dataPayoff::sole($this->platform)->fetchAll($where, '*');
        foreach ($res as &$row) {
            $row['user'] = servUser::sole($this->platform)->uid2profile($row['uid']);
            $row['status'] = servOrder::STATUS_MAP[$row['order_status']];
            $row['item'] = servMoney::ITEM_MAP[$row['i_item']];
        }
        return $res;
    }


}