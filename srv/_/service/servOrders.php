<?php


namespace _;


use Core\unitInstance;

class servOrders extends serv_
{
    const PAY_WAY_WXM = 1; // 微信公众号
    const PAY_WAY_WXA = 3; // 微信小程序
    const PAY_WAY_WXS = 4; // 微信原生扫码

    const MAP_WAY = [
        self::PAY_WAY_WXM => 'wxm',
        self::PAY_WAY_WXA => 'wxa',
        self::PAY_WAY_WXS => 'wxs',
    ];

    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }


    public function inquiry($sn, $read=false)
    {
        $res = [];
        if (substr($sn, 0, 2) == 'UO') {
            $res = servUnionOrder::sole($this->platform)->inquiry($sn);
        }
        if ($sn[0] == 'O') {
            $res = servOrder::sole($this->platform)->inquiry($sn);
        }
        if ($read) { // 外部展示时，资金单位转为元
            foreach (['order_price', 'order_total', 'order_amount', 'charge', 'surplus', 'deduct'] as $field) {
                $res[$field] /= 100;
            }
            foreach ($res['list'] as &$item) {
                $item['price'] /= 100;
            }
        }
        return $res;
    }

    public function purchase($sn)
    {
        if (substr($sn, 0, 2) == 'UO') {
            return servUnionOrder::sole($this->platform)->purchase($sn, 0);
        }
        if ($sn[0] == 'O') {
            return servOrder::sole($this->platform)->purchase($sn, 0);
        }
        return false;
    }

    public function recreate($sn)
    {
        if (substr($sn, 0, 2) == 'UO') {
            $dao = dataUnionOrder::sole($this->platform);
            $pre = $dao->fetchOne(['sn' => $sn], ['i_type', 'uid', 'order_set', 'origin_id', 'order_amount', 'extra', 'i_status']);
            if ($pre['i_status'] > 0) {
                return $sn;
            } else {
                $res = $dao->_append($pre);
                return $res['sn'];
            }
        }
        if ($sn[0] == 'O') {
            $dao = dataOrder::sole($this->platform);
            $pre = $dao->inquireOne(['sn' => $sn], '*');
            if ($pre['i_status'] > 0) {
                return $sn;
            } else {
                $res = $dao->append($pre['uid'], $pre['lesson_id'], $pre['origin_id'], $pre['order_amount'], $pre['i_type'], $pre['extra']);
                return $res['sn'];
            }
        }
    }

    public function charge($orderSn, $transactionId, $paidAmount, $payway)
    {
        if (
            strpos($orderSn, data::SN_ORDER) === 0 &&
            $orderInfo = dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], 'uid,lesson_id,order_amount,origin_id,i_status')
        ) {
            if ($orderInfo['i_status'] > 0) {
                return true;
            } else {
                $res = servOrder::sole($this->platform)->purchase($orderSn, $paidAmount, [
                    'pay_sn' => $transactionId,
                    'i_pay_way' => $payway,
                ]);
                if ($res) {
                    $lessonSn = servLesson::sole($this->platform)->id2sn($orderInfo['lesson_id']);
                    servPromote::sole($this->platform)->sendPromoteMsg($orderInfo['uid'], $lessonSn);
                }
                return $res;
            }
        }
        if (
            strpos($orderSn, data::SN_UNION_ORDER) === 0 &&
            $orderInfo = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $orderSn], ['uid', 'i_status', 'extra'])
        ) {
            if ($orderInfo['i_status'] > 0) {
                return true;
            } else {
                $res = servUnionOrder::sole($this->platform)->purchase($orderSn, $paidAmount, [
                    'pay_sn' => $transactionId,
                    'i_pay_way' => $payway
                ]);
                if ($res) {
                    if ($seriesSn = $orderInfo['extra']['series_sn']) {
                        servPromote::sole($this->platform)->sendPromoteMsg($orderInfo['uid'], $seriesSn);
                    }
                }
                return $res;
            }
        }
    }

}