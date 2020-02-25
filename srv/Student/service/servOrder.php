<?php


namespace Student;


use _\wdgtLang;
use Core\unitInstance;

class servOrder extends \_\servOrder
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

    public function bill($uid, $cursor, $limit)
    {
        $res = dataOrder::sole($this->platform)->slice([
            'uid' => $uid,
            'i_status <> 0',
        ], $cursor, intval($limit));
        $data = [
            'list' => [],
            'cursor' => $cursor
        ];
        foreach ($res as $item) {
            $_data  = [
                'type' => servOrder::TYPE_MAP[$item['i_type']],
                'order_amount' => $item['order_amount']/100,
                'paid_amount' => $item['paid_amount']/100,
                'balance_var' => ($item['order_amount'] - $item['paid_amount']) / 100,
                'pay_way' => servOrder::PAY_WAY_MAP[$item['i_pay_way']],
                'status' => servOrder::STATUS_MAP[$item['i_status']],
                'tms' => $item['tms_create'],
            ];
            $_data['desc'] = dataLesson::sole($this->platform)->fetchOne(['id'=>$item['lesson_id']], 'title', 0);
            $data['list'][] = $_data;
        }
        return $data;
    }

}