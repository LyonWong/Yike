<?php


namespace _;


use Core\library\Payinit;
use Core\unitInstance;

class servUnionOrder extends serv_
{
    use unitInstance;

    const MAP_TYPE = [
        dataUnionOrder::TYPE_SERIES => 'series'
    ];
    const STATUS_MAP = [
        dataUnionOrder::STATUS_BOOK => 'book',
        dataUnionOrder::STATUS_PAID => 'paid',
        dataUnionOrder::STATUS_FIRM => 'firm',
        dataUnionOrder::STATUS_REFUND => 'refund'
    ];

    const GROUP_STATUS_MAP = [
        dataUnionOrder::STATUS_BOOK => 'enlist',
        dataUnionOrder::STATUS_PAID => 'finish',
    ];
    const PAY_WAY_MAP = [
        0 => null,
        dataOrder::PAY_WAY_WEIXIN => 'weixin',
        dataOrder::PAY_WAY_WXA => 'wxa',
    ];

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($iType, $uid, $amount, $originId, $extra = [])
    {
    }


    public function createGroup($target, $notice, $uid, $originId)
    {
        $daoUnionOrder = dataUnionOrder::sole($this->platform);
        $extra = [
            'notice' => $notice,
            'target' => $target
        ];
        return $res = $daoUnionOrder->_append([
            'i_type' => dataUnionOrder::TYPE_GROUP,
            'uid' => $uid,
            'order_set' => '[]',
            'origin_id' => $originId,
            'extra' => json_encode($extra),
        ])['sn'];
    }

    public function groupProfile($unionSn)
    {

        $extra = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $unionSn], 'extra', 'extra');
        $sn = json_decode($extra, true)['target'] ?? '';
        if (strpos($sn, data::SN_SERIES) === 0) {
            $type = 'series';
            $detail = servLessonSeries::sole($this->platform)->detail($sn);
            $title = $detail['name'];
            $teacher = $detail['teacher'];
            $cover = $detail['introduce']['cover'] ??  '';

        } elseif (strpos($sn, data::SN_LESSON) === 0) {
            $type = 'lesson';
            $detail = servLesson::sole($this->platform)->detail($sn);
            $title = $detail['title'];
            $cover = $detail['cover'];
            $teacher = $detail['teacher'];
        } else {
            return null;
        }
        return [
            'type' => $type,
            'sn' => $sn,
            'title' => $title,
            'cover' => $cover,
            'teacher' => $teacher
        ];

    }

    public function groupStatus($unionSn)
    {
        $iStatus = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $unionSn], 'i_status', 'i_status');
        return self::GROUP_STATUS_MAP[$iStatus];
    }

    public function groupSettle($unionSn)
    {
        $detail = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $unionSn], ['order_set', 'order_amount', 'paid_amount', 'pay_sn','extra']);
    }

    public function groupJoin($unionSn, $uid, $remark)
    {

        $unionDetail = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $unionSn], ['order_set','origin_id','extra']);
        $target = json_decode($unionDetail['extra'], true)['target'] ?? '';
        $orderSet = json_decode($unionDetail['order_set'],true);
        if (strpos($target, data::SN_SERIES) === 0) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $res = $this->bookSeries($target,$usn,$unionDetail['origin_id']);
            $orderSet[] = $res['sn'];



        } elseif (strpos($target, data::SN_LESSON) === 0) {
            $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $target], ['id', 'price', 'category']);
            $extra = [

            ];
            $id = dataOrder::sole($this->platform)->append($uid, $lesson['id'],$unionDetail['origin_id'], $lesson['price'], dataOrder::TYPE_ENROLL, $extra)['id'];
            $orderSet[] = $id;

        } else {
            return null;
        }


    }

    public function checkLessons($seriesSn, $usn)
    {
        $srvLesson = servLesson::sole($this->platform);

        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $seriesSn], ['uid', 'name', 'scheme', 'lesson_ids']);
        $lessons = dataLesson::sole($this->platform)->fetchByIDs(
            $series['lesson_ids'],
            ['id', 'sn', 'tuid', 'title', 'price', 'extra'],
            ['i_step>0']
        );

        foreach ($lessons as $i => $item) {
            //过滤没勾选的课程
            $item['extra'] = json_decode($item['extra'], true);
            $categoryCheck = $item['extra']['category_check'] ?? 0;
            unset($item['extra']);
            if (!$categoryCheck) {
                unset ($lessons[$i]);
                continue;
            }

            if ($srvLesson->isEnrolled($item['id'], $uid)) {
                unset ($lessons[$i]);
            }
        }
        return $lessons;
    }


    public function bookSeries($seriesSn, $usn, $originId)
    {
        $srvLesson = servLesson::sole($this->platform);
        $daoUnionOrder = dataUnionOrder::sole($this->platform);
        $srvPromote = servPromote::sole($this->platform);

        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $seriesSn], ['id', 'uid', 'name', 'scheme', 'lesson_ids']);
        if (!$series) {
            return false;
        }
        $lessons = dataLesson::sole($this->platform)->fetchByIDs(
            $series['lesson_ids'],
            ['id', 'sn', 'tuid', 'title', 'price', 'extra'],
            ['i_step>0'],
            "order by plan->'$.dtm_start'"
        );

        $priceTotal = $pricePrime = 0; //订单中需要报名的课程总价
        $lessonIds = [];
        foreach ($lessons as $i => $item) {
            //过滤没勾选的课程
            $item['extra'] = json_decode($item['extra'], true);
            $categoryCheck = $item['extra']['category_check'] ?? 0;
            unset($item['extra']);
            if (!$categoryCheck) {
                unset ($lessons[$i]);
                continue;
            } else {
                $priceTotal += $item['price'];
            }

            if ($srvLesson->isEnrolled($item['id'], $uid)) {
                unset ($lessons[$i]);
            } else {
                $pricePrime += $item['price'];
                $lessonIds[] = $item['id'];
                $lessons[$i] = servLesson::sole($this->platform)->boost($item);
            }
        }
        $priceSeries = $series['scheme']['price'] ?? $priceTotal; //未设置系列价时使用单课总价
        $priceOrder = $priceTotal ? round($pricePrime / $priceTotal * $priceSeries) : 0;

        if ($psn = $srvPromote->check($uid, 0, $series['id'])) {
            $promote = $srvPromote->info($psn);
            $originId = $promote['origin_id'] ?? $originId;
            $deduct = min($priceOrder, $priceTotal ? round($pricePrime / $priceTotal * $promote['discount']) : 0);
            $orderAmount = $priceOrder - $deduct;
        } else {
            $orderAmount = $priceOrder;
            $deduct = 0;
        }
//        $orderAmount = $priceOrder;

        //计算资金需求
        $keep = dataUserKeep::sole($this->platform)->obj($uid, dataUserKeep::ITEM_MONEY);
        $charge = min($keep['balance'], $orderAmount); //账户余额支付的部分
        $surplus = $orderAmount - $charge; //需要第三方支付的部分

        $res = [
            'series_name' => $series['name'],
            'order_total' => $pricePrime / 100,
            'order_price' => $priceOrder / 100,
            'order_amount' => $orderAmount / 100,
            'margin' => ($keep['balance'] - $orderAmount) / 100,
            'charge' => $charge / 100,
            'deduct' => $deduct / 100,
            'surplus' => $surplus / 100,
            'lessons' => array_values($lessons),
        ];

        $extra = [
            'cost' => [
                'total' => $priceTotal, //全系列单课总价(勾选)
                'series' => $priceSeries, //全系列打包价
                'prime' => $pricePrime, //订单单课总价
                'order' => $priceOrder, //订单加权价格
                'deduct' => $deduct,
            ],
            'lesson_ids' => $lessonIds,
            'series_sn' => $seriesSn,
            'promote' => $psn ?: null,
        ];
        if (count($res['lessons']) == 0) {
            $res['order'] = null;
        } else {
            $res['order'] = $daoUnionOrder->_append([
                'i_type' => dataUnionOrder::TYPE_SERIES,
                'uid' => $uid,
                'order_set' => '[]',
                'origin_id' => $originId,
                'order_amount' => $orderAmount,
                'extra' => json_encode($extra),
            ])['sn'];
        }

        return $res;
    }

    public function purchase($unionOrderSn, $paidAmount, $update = [])
    {
        $daoUnionOrder = dataUnionOrder::sole($this->platform);

        $union = $daoUnionOrder->inquireOne(
            ['sn' => $unionOrderSn],
            ['uid', 'order_set', 'order_amount', 'origin_id', 'i_status', 'extra']
        );
        if ($union['i_status'] != dataUnionOrder::STATUS_BOOK) {
            return false;
        }
        $cost = $union['extra']['cost'];
        $extra = array_merge($update['extra'] ?? [], $union['extra']);
        $balance = dataMoney::sole($this->platform)->balance($union['uid']);
        $charge = $union['order_amount'] - $paidAmount; //需要余额支付的部分
        if ($charge > $balance) {
            return false;
        }
        $srvMoney = servMoney::sole($this->platform);
        $varMoney = $srvMoney->change(
            dataMoney::ITEM_PURCHASE,
            $union['uid'],
            -$charge,
            ['uo' => $unionOrderSn]
        );
        $extra['var'] = $varMoney;
        $amount = array_merge(
            [
                'order_amount' => $union['order_amount'],
            ],
            $srvMoney->initUserKeep($varMoney)
        );
        $daoOrder = dataOrder::sole($this->platform);

        //分配到每节课
        $lessonIds = $union['extra']['lesson_ids'];

        $lessons = dataLesson::sole($this->platform)->fetchByIDs($lessonIds, ['id', 'sn', 'tuid', 'price']);
        $ordersIds = [];
        $addUpPrice = 0;
        $addUpAmount = [];
        $payoffs = [];
        do {
            $_lesson = array_pop($lessons);
            $_count = count($lessons);
            $addUpPrice += $_lesson['price'];
            $addUpWeight = $addUpPrice / ($cost['prime']?:1);
            $_order = [
                'lesson_id' => $_lesson['id'],
            ];
            foreach ($amount as $key => $val) {
                $_order[$key] = round($val * $addUpWeight) - ($addUpAmount[$key] ?? 0);
                $addUpAmount[$key] = ($addUpAmount[$key]??0) + $_order[$key];
            }
            $_order['paid_amount'] = $_order['order_amount'] + $_order['cash'] + $_order['voucher'];
            $_extra = [
                'series' => $extra['series_sn'],
                'price' => $_lesson['price'],
                'var' => [
                    'voucher' => $_order['voucher'],
                    'cash' => $_order['cash'],
                ],
            ];
            $_data = [
                'i_type' => dataOrder::TYPE_SERIES,
                'uid' => $union['uid'],
                'lesson_id' => $_lesson['id'],
                'origin_id' => $union['origin_id'],
                'order_amount' => $_order['order_amount'],
                'paid_amount' => $_order['paid_amount'],
                'i_pay_way' => $update['i_pay_way'] ?? 0,
                'pay_sn' => $unionOrderSn,
                'i_status' => dataOrder::STATUS_PAID,
                'extra' => json_encode($_extra),
            ];
            $_data['id'] = $daoOrder->_append($_data)['id'];
            $_data['extra'] = $_extra;
            $ordersIds[] = $_data['id'];


            // 每个子订单独立计算分成金额，并汇总
            $_payoff = servPayoff::sole($this->platform)->fixPayoff($_data);
            foreach ($_payoff as $_key => $_item) {
                if (isset($payoffs[$_key])) {
                    $payoffs[$_key]['amount'] += $_item['amount'];
                } else {
                    $payoffs[$_key] = $_item;
                }
            }

            $ip = \input::ip();
            stats\servLesson::sole($this->platform)->varEnroll($_lesson['id'], $union['origin_id']);
            stats\servLesson::sole($this->platform)->varIncome($_lesson['id'], $union['origin_id'], intval($_order['order_amount']));
            dataLessonAccess::sole($this->platform)->append($_lesson['id'], $union['uid'], dataLessonAccess::EVENT_ENROLL, [
                'origin' => $union['origin_id'],
                'ip' => $ip
            ]);
            /*
            $_expect = servPayoff::sole($this->platform)->calcExpect($_data);
            foreach ($_expect as $_uid => $_val) {
                dataUserKeep::sole($this->platform)->varAttr($_uid, dataUserKeep::ITEM_MONEY, '$.expect', $_val);
            }
            */
        } while ($_count > 0);
        $update['order_set'] = json_encode($ordersIds);
        $update['paid_amount'] = $paidAmount;
        $update['i_status'] = dataUnionOrder::STATUS_PAID;
        $update['extra'] = json_encode($extra);
        $daoUnionOrder->update($update, ['sn' => $unionOrderSn]);
        if ($extra['promote']) {
//            servMpMsg::sole($this->platform)->sendPromoteMsg($extra['promote'], $union['uid']);
            servPromote::sole($this->platform)->rankModify($extra['series_sn'], $union['origin_id'], 1);
        }
        // 推送佣金通知
        foreach ($payoffs as $item) {
            // 推送佣金通知（分享奖励+渠道分销）
            if ($item['uid'] && $item['i_item'] == dataMoney::ITEM_LESSON_INCOME) {
                servMpMsg::sole($this->platform)->sendCommissionMsg($item['uid'], $union['uid'], $extra['series_sn'], $item['amount'], '开设');
            }
            if ($item['i_item'] == dataMoney::ITEM_COMMISSION) {
                servMpMsg::sole($this->platform)->sendCommissionMsg($item['uid'], $union['uid'], $extra['series_sn'], $item['amount'], '推荐');
            }
        }
        servMpMsg::sole($this->platform)->sendSeriesMsg($extra['series_sn'], $union['uid']);
        return true;
    }

    public function refund($unionOrderSn, $refundSn, $refundAmount)
    {
        $order = dataUnionOrder::sole($this->platform)->fetchOne(['sn' => $unionOrderSn], ['paid_amount', 'refund_amount']);
        if ($order['paid_amount'] - $order['refund_amount'] - $refundAmount < 0) {
            return false;
        }
        $Pay = Payinit::weixin('weixin');
        $Pay->_params = [
            'appid' => $Pay->appId, //APP ID
            'mch_id' => $Pay->mchId, //商户号
            'out_trade_no' => $unionOrderSn,//内部订单号
            'out_refund_no' => $refundSn, //内部退款单号
            'total_fee' => $order['paid_amount'],
            'refund_fee' => $refundAmount,
            'op_user_id' => $Pay->mchId,
        ];
        $res = $Pay->createRefundData();
        if ($res) {
            dataUnionOrder::sole($this->platform)->update(
                ['refund_amount' => $order['refund_amount'] + $refundAmount],
                ['sn' => $unionOrderSn]
            );
            return true;
        } else {
            return false;
        }
    }

    public function isPurchased($seriesSn, $uid)
    {
        $res = dataUnionOrder::sole($this->platform)->fetchOne(
            [
                'i_type' => dataUnionOrder::TYPE_SERIES,
                'uid' => $uid,
                'i_status <> 0',
                "extra->'$.series_sn'=?" => [$seriesSn]
            ], 'count(*)', 0
        );
        return boolval($res);
    }

    public function inquiry($sn)
    {
        $res = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $sn], '*');
        $keep = dataUserKeep::sole($this->platform)->obj($res['uid'], dataUserKeep::ITEM_MONEY);
        $charge = min($keep['balance'], $res['order_amount']); //账户余额支付的部分
        $surplus = $res['order_amount'] - $charge; //需要第三方支付的部分
        if ($seriesSn = $res['extra']['series_sn'] ?? null) {
            $title = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $res['extra']['series_sn']], ['name'], 0);
        } else {
            $title = '联合订单';
        }
        $lessons = dataLesson::sole($this->platform)->fetchByIDs(
            $res['extra']['lesson_ids'],
            ['title', 'price'],
            [],
            "order by plan->'$.dtm_start'"
            );
        $data = [
            'sn' => $sn,
            'title' => $title,
            'uid' => $res['uid'],
            'order_total' => $res['extra']['cost']['prime'],
            'order_price' => $res['extra']['cost']['order'],
            'order_amount' => $res['order_amount'],
            'deduct' => $res['extra']['cost']['deduct'],
            'charge' => $charge,
            'surplus' => $surplus,
            'status' => self::STATUS_MAP[$res['i_status']],
            'list' => $lessons
        ];
        return $data;
    }

}
