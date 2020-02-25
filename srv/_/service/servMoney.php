<?php


namespace _;


use Core\unitInstance;

class servMoney extends serv_
{
    use unitInstance;

    const ITEM_MAP = [
        dataMoney::ITEM_SERVICE_FEE => 'service_fee',
        dataMoney::ITEM_LESSON_INCOME => 'lesson_income',
        dataMoney::ITEM_TOPUP => 'topup',
        dataMoney::ITEM_REBATE => 'rebate',
        dataMoney::ITEM_RETURN => 'return',
        dataMoney::ITEM_COMMISSION => 'commission',
        dataMoney::ITEM_DRAWCASH => 'drawcash',
        dataMoney::ITEM_LESSON_REFUND => 'lesson_refund',
        dataMoney::ITEM_PURCHASE => 'purchase',
        dataMoney::ITEM_DEDUCT => 'deduct',
        dataMoney::ITEM_REWARD => 'reward',
        dataMoney::ITEM_ADMIRE => 'admire'
    ];

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function overview($uid)
    {
        $res = dataUserKeep::sole($this->platform)->get($uid, dataUserKeep::ITEM_MONEY, 'obj');
        $data = json_decode($res['obj'], true);
        $payoff = dataPayoff::sole($this->platform)->fetchAll(['uid' => $uid], "order_status, sum(amount) as amount", 0, 1, "group by order_status");
        foreach ($payoff as $i => $v) {
            $key = servOrder::STATUS_MAP[$i];
            $data[$key] = $v;
        }
        return $data;
    }

    public function orders($uid, $cursor, $limit)
    {
        $where = [
            'uid' => $uid
        ];
        if ($cursor) {
            $where['order_id<?'] = [$cursor];
        }
        $orders = dataPayoff::sole($this->platform)->fetchAll($where,
            "order_id, max(order_status) as order_status, sum(amount) as amount",
            null, null,
            "group by order_id order by order_id desc limit $limit");
        foreach ($orders as &$order) {
            $_order = dataOrder::sole($this->platform)->fetchOne(['id' => $order['order_id']],
                ['sn', 'lesson_id', 'tms_create']);
            $_order['status'] = servOrder::STATUS_MAP[$order['order_status']];
            unset ($order['order_status']);
            $_lesson = servLesson::sole($this->platform)->id2info($_order['lesson_id'], 'title,category,extra->>"$.cover" as cover');
            $_lesson['cover'] = \view::upload($_lesson['cover']);
            $order = array_merge($order, $_order, $_lesson);
        }
        return $orders;
    }

    /**
     * 修改money表记录，同时更新userKeep数据
     * @param int $item
     * @param int $uid
     * @param int $amount
     * @param array $args
     * @param string $sign
     * @return array
     */
    public function change(int $item, int $uid, int $amount, array $args = [], string $sign = '')
    {
        $_args = $args;
        unset($_args['var']);
        $id = dataMoney::sole($this->platform)->append($item, $uid, $amount, $_args, $sign);
        if (!$id) {
            return [];
        }
        $keep = dataUserKeep::sole($this->platform)->obj($uid, dataUserKeep::ITEM_MONEY);
        $preKeep = $this->initUserKeep($keep);
        $newKeep = $this->dealUserKeep($item, $amount, $keep, $args);
        dataUserKeep::sole($this->platform)->set($uid, dataUserKeep::ITEM_MONEY, [
            'num' => $newKeep['balance'],
            'obj' => json_encode($newKeep)
        ]);
        return $this->diffUserKeep($newKeep, $preKeep);
    }

    public function balance($uid)
    {
        return dataMoney::sole($this->platform)->balance($uid) / 100;
    }

    public function debit($uid, $cursor, $limit)
    {
        $res = dataMoney::sole($this->platform)->slice([
            'uid' => $uid,
            'amount<>0'
        ], $cursor, $limit);
        $data = [
            'list' => [],
            'cursor' => $res['cursor'],
        ];
        unset($res['cursor']);
        foreach ($res as $item) {
            $_data = [
                'item' => servMoney::ITEM_MAP[$item['i_item']],
                'amount' => $item['amount'] / 100,
                'tms' => $item['tms'],
            ];
            $_args = json_decode($item['args'], true);
            $_data['desc'] = wdgtLang::dict($_data['item']);
            switch ($item['i_item']) {
                case dataMoney::ITEM_RETURN:
                case dataMoney::ITEM_PURCHASE:
                    $lessonId = dataOrder::sole($this->platform)->fetchOne(['id' => $_args['order_id']], 'lesson_id', 0);
                    $title = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'title', 0);
                    $_data['desc'] .=  '・'.$title;
                    break;
                case dataMoney::ITEM_REBATE:
                    $_data['desc'] .= '・'.$_args['info'];
                    break;
                case dataMoney::ITEM_REWARD:
                    $info = servPromote::sole($this->platform)->info($_args['psn']);
                    $uname = dataUser::sole($this->platform)->fetchOne(['id' => $info['uid']], 'name', 0);
                    $ltitle = dataLesson::sole($this->platform)->fetchOne(['id' => $info['lesson_id']], 0);
                    $_data['desc'] .= '・'."帮 $uname 砍价 $ltitle";
                    break;
                case dataMoney::ITEM_COMMISSION:
                    $lessonId = dataOrder::sole($this->platform)->fetchOne(['sn' => $_args['order_sn']], 'lesson_id', 0);
                    $title = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'title', 0);
                    $_data['desc'] .= '・'.$title;
                    break;
                case dataMoney::ITEM_DEDUCT:
                    break;
                case dataMoney::ITEM_ADMIRE:
                    $title = dataLesson::sole($this->platform)->fetchOne(['id' => $_args['lesson_id']], 'title', 0);
                    $uname = dataUser::sole($this->platform)->fetchOne(['id' => $_args['uid']], 'name', 0);
                    $_data['desc'] .= '・'."$uname 赞了《{$title}》";
                    break;

            }
            $data['list'][] = $_data;
        }
        return $data;
    }

    public function initUserKeep(&$data)
    {
        foreach (['balance', 'payoff', 'cash', 'voucher'] as $item) {
            $data[$item] = $data[$item] ?? 0;
        }
        return $data;
    }

    public function flushUserKeep($uid)
    {
        $rows = dataMoney::sole($this->platform)->fetchAll(['uid' => $uid], ['i_item', 'amount', 'balance', 'args']);
        $data = [];
        $this->initUserKeep($data);
        foreach ($rows as $row) {
            $_args = json_decode($row['args'], true);
            if (isset($_args['order_id'])) {
                $_extra = dataOrder::sole($this->platform)->fetchOne(['id' => $_args['order_id']], 'extra', 0);
                $_extra = json_decode($_extra, true);
            } else {
                $_extra = [];
            }
            $data = $this->dealUserKeep($row['i_item'], $row['amount'], $data, $_extra);
        }
        dataUserKeep::sole($this->platform)->set($uid, dataUserKeep::ITEM_MONEY, [
            'num' => $data['balance'],
            'obj' => json_encode($data)
        ]);
        return $data;
    }

    public function diffUserKeep($newKeep, $preKeep)
    {
        $var = [];
        foreach ($newKeep as $key => $val) {
            if ($_var = $val - $preKeep[$key]) {
                $var[$key] = $_var;
            }
        }
        return $var;
    }

    /**
     * @param $item
     * @param $amount
     * @param $keep
     * @param $args
     * @return mixed $keep <balance,payoff,cash,voucher>
     */
    public function dealUserKeep($item, $amount, $keep, $args)
    {
        $keep['balance'] += $amount;
        switch ($item) {
            case dataMoney::ITEM_LESSON_INCOME:
            case dataMoney::ITEM_LESSON_REFUND:
            case dataMoney::ITEM_COMMISSION:
            case dataMoney::ITEM_DEDUCT:
                $keep['payoff'] += $amount;
                $keep['cash'] += $amount;
                break;
            case dataMoney::ITEM_REBATE:
            case dataMoney::ITEM_REWARD:
                $keep['voucher'] += $amount;
                break;
            case dataMoney::ITEM_RETURN:
                if (isset($args['var'])) { // 有记录时，分别返还代金券和现金余额
                    $keep['voucher'] -= $args['var']['voucher'] ?? 0;
                    $keep['cash'] -= $args['var']['cash'] ?? 0;
                } else { // 否则都作为代金券返还
                    $keep['voucher'] += $amount;
                }
                break;
            case dataMoney::ITEM_TOPUP:
            case dataMoney::ITEM_DRAWCASH:
                $keep['cash'] += $amount;
                break;
            case dataMoney::ITEM_PURCHASE:
                $margin = -$amount;
                if ($keep['voucher'] > 0) { // 优先使用代金券
                    $_voucher = min($keep['voucher'], $margin);
                    $margin -= $_voucher;
                    $keep['voucher'] -= $_voucher;
                }
                if ($margin > 0) { // 不足部分由现金支付
                    $keep['cash'] -= $margin;
                }
                break;
        }
        return $keep;
    }

    public function moneyListByTime($tmsStart, $tmsEnd)
    {
        $where = [
            'tms between ? and ?' => [$tmsStart, $tmsEnd],
        ];
        $list = dataMoney::sole($this->platform)->fetchAll($where, ['uid', 'i_item', 'amount', 'balance']);
        $data = [];
        foreach ($list as $item) {
            $data[$item['uid']][] = $item;
        }
        return $data;
    }

}