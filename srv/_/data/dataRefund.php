<?php


namespace _;


use Core\unitInstance;

class dataRefund extends dataSole
{
    use unitInstance;
    const TABLE = 'refund';

    const STATUS_ASKING = 0; //申请退款
    const STATUS_FINISH = 1; //完成退款

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform = null)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($orderId, $unionId, $uid, $amount, $iStatus = 0): string
    {
        $data = [
            'order_id' => $orderId,
            'union_id' => $unionId,
            'uid' => $uid,
            'amount' => $amount,
            'i_status' => $iStatus,
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_REFUND);
            self::mysql()->insert(self::TABLE, $data);
            $id = (int)self::mysql()->lastInsertId();
            if (--$try == 0)  {
                return false;
            }
        } while (!$id);
        return $data['sn'];
    }
}