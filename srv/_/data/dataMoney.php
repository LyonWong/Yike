<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class dataMoney extends dataSole
{
    use unitInstance;

    const TABLE = 'money';

    //收入
    const ITEM_SERVICE_FEE = 0; // 平台服务费
    const ITEM_LESSON_INCOME = 1; //课程报名收入
    const ITEM_TOPUP = 2; // 充值
    const ITEM_REBATE = 3; // 返现
    const ITEM_RETURN = 4; // 返还
    const ITEM_COMMISSION = 5; //佣金
    const ITEM_REWARD = 6; //鼓励金
    const ITEM_ADMIRE = 7; // 赞赏

    //支出
    const ITEM_DRAWCASH = -1; // 提现
    const ITEM_LESSON_REFUND = -2; //退款
    const ITEM_PURCHASE = -3; //购买
    const ITEM_DEDUCT = -4; //扣除


    /**
     * @param $platform
     * @return self
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

    public function append(int $item, int $uid, int $amount, array $args = [], string $sign = '')
    {
        $preBalance = $this->balance($uid);
        $this->insert([
            'i_item' => $item,
            'uid' => $uid,
            'amount' => $amount,
            'balance' => $preBalance + $amount,
            'args' => json_encode($args),
            'sign' => crc32($sign)
        ]);
        return $this->mysql->lastInsertId();
    }

    public function balance(int $uid)
    {
        return $this->mysql->select($this->TABLE, ['balance'], ['uid' => $uid], 'order by tms desc limit 1')->fetch(0);
    }

    public function slice($where, &$cursor, int $limit)
    {
        if ($cursor !== null) {
            $where['tms<?'] = [Tool::timeDecode($cursor)];
        }
        $res = $this->mysql->select($this->TABLE, '*', $where, "order by tms desc limit $limit")->fetchAll();
        $res['cursor'] = Tool::timeEncode(end($res)['tms'] ?? '');
        return $res;
    }

    public function sumAmount($uid, $item = 0)
    {
        $where['uid'] = $uid;
        if ($item >= 0) {
            $where[] = 'i_item > 0';
        } else {
            $where[] = 'i_item < 0';
        }
        $sum = $this->mysql
            ->s("select sum(amount) as amount from {$this->TABLE}")
            ->w($where)
            ->e()
            ->fetch();
        if ($sum['amount']) {
            $sum['amount'] /= 100;
        }
        return $sum['amount'];
    }
}