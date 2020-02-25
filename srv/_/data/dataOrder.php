<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class dataOrder extends dataSole
{
    use unitInstance;

    const TABLE = 'order';

    const TYPE_ENROLL = 1; // 普通订单
    const TYPE_SERIES = 2; // 系列订单
    const TYPE_ADMIRE = -1; // 赞赏订单

    const PAY_WAY_WEIXIN = 1; // 微信支付
    const PAY_WAY_UNION = 2; //联合订单
    const PAY_WAY_WXA = 3; // 微信小程序支付

    const STATUS_BOOK = 0; // 预定
    const STATUS_PAID = 1; // 付款
    const STATUS_FIRM = 2; // 确认，不可退款
    const STATUS_DONE = 3; // 资金已结算
    const STATUS_REFUND = -1; // 退款


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


    public function _append($data)
    {
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_ORDER);
            self::mysql()->insert(self::TABLE, $data);
            $id = (int)self::mysql()->lastInsertId();
        } while (!$id && --$try>0);
        return ['id' => $id, 'sn'=>$data['sn']];
    }

    /**
     * @param $uid
     * @param $lessonId
     * @param $originId
     * @param $amount
     * @param $iType
     * @param array $extra
     * @return mixed <id,sn>
     */
    public function append($uid, $lessonId, $originId, $amount, $iType, $extra=[])
    {
        $data = [
            'i_type' => $iType,
            'uid' => $uid,
            'lesson_id' => $lessonId,
            'origin_id' => $originId,
            'order_amount' => $amount,
            'i_status' => self::STATUS_BOOK,
            'extra' => json_encode($extra, JSON_FORCE_OBJECT),
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_ORDER);
            $this->insert($data);
            $id = $this->mysql->lastInsertId();
            if (--$try < 0) {
                return false;
            }
        } while (!$id);
        return ['id' => $id, 'sn' => $data['sn']];
    }

    public function getInfo($uid, $lessonId)
    {
        $res = $this->mysql->select($this->TABLE,
            "id, uid, i_type, lesson_id, sn, order_amount, paid_amount, payoff_amount, i_pay_way, pay_sn, origin_id, i_status,extra",
            ['i_type>0', 'uid' => $uid, 'lesson_id' => $lessonId],
            'order by id desc')
            ->fetch();
        if ($res) {
            $res['extra'] = json_decode($res['extra'], true);
        }
        return $res;
    }

    public function slice($where, &$cursor, int $limit)
    {
        if ($cursor) {
            $where['id<?'] = [$cursor];
        }
        $res = $this->mysql->select($this->TABLE, '*', $where, "order by id desc limit $limit")->fetchAll();
        $cursor = end($res)['id'];
        return $res;
    }

    public function lastOrdersByLesson($lessonId, array $filter=[])
    {
        $where = array_merge($filter, ['lesson_id'=>$lessonId]);
        $ids = $this->mysql->select($this->TABLE, "max(id), uid", $where, 'group by uid')->fetchAll(null, 0);
        if (count($ids)) {
            $m = $this->mysql->s("select id,uid,i_status from `$this->TABLE`");
            if ($filter) {
                $m = $m->w($filter)->v("and id in ", $ids);
            } else {
                $m = $m->v('where id in ', $ids);
            }
            $res = $m->e()->fetchAll();
        } else {
            $res = [];
        }
        return $res;
    }

    public function fetchByUserLessons($uid, array $lessonIds = [], array $filter=[])
    {
        if (count($lessonIds) == 0) {
            return [];
        }
        $where = array_merge(['uid'=>$uid], $filter);
        $orders = $this->mysql->s("select * from `$this->TABLE`")->w($where)->v("and lesson_id in ", $lessonIds)->e()->fetchAll();
        return $orders;
    }

    protected function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key) {
                case 'extra':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }

}