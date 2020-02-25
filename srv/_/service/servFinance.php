<?php


namespace _;


use Core\unitInstance;

class servFinance extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function income($groupBy, $dateStart, $dateEnd, $tsn = null)
    {
        $daoMoney = dataMoney::sole($this->platform);
        $daoOrder = dataOrder::sole($this->platform);
        $daoLesson = dataLesson::sole($this->platform);
        $moneyData = $daoMoney->fetchAll(['tms between ? and ?' => [strToDate($dateStart, 'Y-m-d 00:00:00'), strToDate($dateEnd, 'Y-m-d 23:59:59')]],
            '*'
        );
        $orderDict = $daoOrder->fetchAll([
            'tms_update between ? and ? ' => [strToDate("$dateStart -3 days", 'Y-m-d 00:00:00'), strToDate($dateEnd, 'Y-m-d 23:59:59')],
            'i_status<>0',
        ],
            '*',
            'sn'
        );
        if ($tsn[0] == 'S') {
            $lessonFilter = $daoLesson->fetchAll(['category' => $tsn], ['id', 'i_step'], 0, 1);
        } elseif ($tsn[0] == 'L') {
            $lessonFilter = $daoLesson->fetchAll(['sn' => $tsn], ['id', 'i_step'], 0, 1);
        } else {
            $lessonFilter = [];
        }
        $moneyDict = $groupDict = [];
        $result = [
            'TOTAL' => [
                'KEY' => 'TOTAL',
                'order_amount' => 0,
                'weixin_fee' => 0,
                'service_fee' => 0,
                'payoff_amount' => 0,
                'commission' => 0,
                'share_amount' => 0,
            ],
        ];
        foreach ($moneyData as $item) {
            $args = json_decode($item['args'], true);
            if ($args['order_sn']) {
                $osn = $args['order_sn'];
            } else {
                continue; //跳过和订单无关的记录
            }
            $moneyDict[$osn][$item['i_item']] = [
                'date' => strToDate($item['tms'], 'Y-m-d'),
                'amount' => $item['amount'],
            ];
            if (empty($orderDict[$osn])) {
                $orderDict[$osn] = $daoOrder->fetchOne(['sn' => $osn], '*');
            }
        }

        foreach ($moneyDict as $osn => $item) {

            $order = $orderDict[$osn];

            if ($lessonFilter && !$lessonFilter[$order['lesson_id']]) {
                continue; // filter by lesson
            }

            $_result = [
                'weixin_fee' => round($order['paid_amount'] * 0.006),
                'service_fee' => round($order['order_amount'] * 0.004),
                'payoff_amount' => $order['payoff_amount'],
            ];

            $_key = $item[dataMoney::ITEM_LESSON_INCOME]['date'] ?? null;
            $key_ = $item[dataMoney::ITEM_LESSON_REFUND]['date'] ?? null;
            $ckey = $item[dataMoney::ITEM_COMMISSION]['date'] ?? null;

            $_commission = $item[dataMoney::ITEM_COMMISSION]['amount'] ?? 0;

            $_result['share_amount'] = $order['order_amount'] - array_sum($_result) - $_commission;
            $_result['order_amount'] = $order['order_amount'];

            if ($groupBy == 'lesson') {
                $_key = $_key ? $order['lesson_id'] : null;
                $key_ = $key_ ? $order['lesson_id'] : null;
                $ckey = $ckey ? $order['lesson_id'] : null;
            }
            if ($_key) {
                foreach ($_result as $i => $v) {
                    $result[$_key][$i] = ($result[$_key][$i] ?? 0) + $v;
                }
            }
            if ($key_) {
                if (!$_commission) {
                    $_result['share_amount'] -= $daoMoney->fetchOne(['i_item' => dataMoney::ITEM_COMMISSION, "args->'$.order_sn'=?"=>[$osn]], 'amount', 0);
                }
                foreach ($_result as $i => $v) {
                    $result[$key_][$i] = ($result[$key_][$i] ?? 0) - $v;
                }
            }
            if ($_commission) {
                $result[$ckey]['commission'] += $_commission;
            }
        }
        foreach ($result as $key => &$item) {
            foreach ($item as $i => &$v) {
                $v /= 100;
                $result['TOTAL'][$i] += $v;
            }
            $item['KEY'] = $this->parseIncomeGroup($groupBy, $key);
        }
        return $result;
    }

    public function parseIncomeGroup($groupBy, $key)
    {
        switch ($groupBy) {
            case 'lesson':
                $key = dataLesson::sole($this->platform)->fetchOne(['id' => $key], 'title', 0);
                break;
        }
        return $key;
    }

    public function incomeSummary($dateStart, $dateEnd, $tsn = null, $originId = null)
    {
        $daoLesson = dataLesson::sole($this->platform);
        $daoMoney = dataMoney::sole($this->platform);
        $where = [
            'tms_update between ? and ?' => [strToDate($dateStart, 'Y-m-d 00:00:00'), strToDate($dateEnd, 'Y-m-d 23:59:59')],
            'i_status' => dataOrder::STATUS_FIRM,
        ];
        if ($tsn) {
            if ($tsn[0] == 'S') {
                $lessonIds = $daoLesson->fetchAll(['category' => $tsn], 'id', null, 0);
            } else {
                $lessonIds = $daoLesson->fetchOne(['sn' => $tsn], 'id', 0);
            }
            $where[] = "lesson_id in (" . implode(',', $lessonIds) . ")";
        }
        if ($originId) {
            $where['origin_id'] = $originId;
        }
        $orderData = dataOrder::sole($this->platform)->fetchAll($where, '*');
        $moneyData = $daoMoney->fetchAll(['tms between ? and ?' => [strToDate($dateStart, 'Y-m-d 00:00:00'), strToDate("$dateEnd +3 days", 'Y-m-d 23:59:59')]],
            'i_item, amount, json_unquote(args->"$.order_sn") as order_sn'
        );
        $moneyOrder = [];
        foreach ($moneyData as $item) {
            if ($item['order_sn']) {
                $moneyOrder[$item['order_sn']][$item['i_item']] = ($moneyOrder[$item['order_sn'][$item['i_item']]] ?? 0) + $item['amount'];
            }
        }
        $result = [];
        foreach ($orderData as $order) {
            $osn = $order['sn'];
            if (empty($moneyOrder[$osn])) {
                $_moneyOrder = $daoMoney->fetchAll("args->'$.order_sn'='$osn'", ['i_item', 'amount'], 0, 1);
                if ($_moneyOrder) {
                    $moneyOrder[$osn] = $_moneyOrder;
                } else {
                    continue; //若无资金记录，跳过
                }
            }
            $_result = [
                'weixin_fee' => round($order['order_amount'] * 0.006),
                'service_fee' => round($order['order_amount'] * 0.004),
                'payoff_amount' => $order['payoff_amount'],
                'commission' => $moneyOrder[$osn][dataMoney::ITEM_COMMISSION] ?? 0,
            ];
            $_result['share_amount'] = $order['order_amount'] - array_sum($_result);
            $_result['order_amount'] = $order['order_amount'];
            foreach ($_result as $i => $v) {
                $result[$i] = ($result[$i] ?? 0) + $v;
            }
        }
        foreach ($result as &$v) {
            $v /= 100;
        }
        return $result;
    }

}