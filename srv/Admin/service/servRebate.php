<?php


namespace Admin;


use _\dataMoney;
use _\servMpMsg;
use Core\unitInstance;

class servRebate extends serv_
{
    use unitInstance;

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function single($uid, $amount, $info = '')
    {
        $var = servMoney::sole($this->platform)->change(
            dataMoney::ITEM_REBATE,
            $uid,
            round($amount * 100),
            ['info' => $info],
            time());
        return boolval($var);
    }

    public function lesson($lessonSn, $percent, $deadline, $info='', $preview = false)
    {
        $where = [
            'lesson_id' => servLesson::sole($this->platform)->sn2id($lessonSn),
            'tms_create <= ?' => [$deadline],
            'i_status' => dataOrder::STATUS_FIRM
        ];
        $res = dataOrder::sole($this->platform)->fetchAll($where,
            ['sn', 'uid', 'order_amount', 'paid_amount', 'payoff_amount', 'i_status', 'tms_create', 'tms_update']);
        $total = 0;
        foreach ($res as &$row) {
            $total += $amount = round($row['order_amount'] * $percent / 100);
            $row['user'] = servUser::sole($this->platform)->uid2profile($row['uid']);
            $row['rebate_amount'] = $amount / 100;
            $row['order_amount'] /= 100;
            $row['paid_amount'] /= 100;
            $row['payoff_amount'] /= 100;
            $row['status'] = servOrder::STATUS_MAP[$row['i_status']];
            if ($preview) {
                continue; // 预览跳过实际执行
            }
            if ($var = servMoney::sole($this->platform)->change(dataMoney::ITEM_REBATE, $row['uid'], round($amount), [
                'order_sn' => $row['sn'],
                'percent' => $percent,
                'info' => $info
            ], $row['sn'])
            ) {
                //推送通知
                servMpMsg::sole($this->platform)->sendCashBackNotice($lessonSn, $row['uid'], $row['rebate_amount']);
            }
        }
        return [
            'count' => count($res),
            'total' => $total / 100,
            'list' => $preview ? $res : []
        ];
    }

}