<?php


namespace _;


use Core\unitInstance;

class dataUnionOrder extends dataSole
{
    use unitInstance;

    const TABLE = 'union_order';

    const TYPE_SERIES = 1; // 系列订单
    const TYPE_GROUP = 2; // 团体订单

    const PAY_WAY_WEIXIN = 1; // 微信支付
    const PAY_WAY_UNION = 2; // 联合订单
    const PAY_PAY_WXA = 3; // 微信小程序支付

    const STATUS_BOOK = 0; // 预定
    const STATUS_PAID = 1; // 付款
    const STATUS_FIRM = 2; // 确认
    const STATUS_REFUND = -1; // 退款
    const STATUS_REJECT = -2; // 拒绝

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function _append($data)
    {
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_UNION_ORDER);
            $this->insert($data);
            $id = $this->mysql->lastInsertId();
        } while (!$id && --$try>0);
        return ['id' => $id, 'sn' => $data['sn']];
    }

    public function append(int $iType, $uid, $orderAmount, $paidAmount, $originId, array $extra=[])
    {
        $data = [
            'i_type' => $iType,
            'uid' => $uid,
            'order_set' => '[]',
            'order_amount' => $orderAmount,
            'paid_amount' => $paidAmount,
            'origin_id' => $originId,
            'extra' => json_encode($extra, JSON_FORCE_OBJECT),
            'i_status' => self::STATUS_BOOK,
        ];
        return $this->_append($data);
    }

    protected function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key) {
                case 'order_set':
                case 'extra':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }
}