<?php


namespace _\api;



use _\dataLessonAccess;
use _\dataOrder;
use _\servLessonSeries;
use _\servMpMsg;
use _\servOrder;
use _\servOrders;
use _\servOrigin;
use _\servRefund;
use _\servUnionOrder;
use _\servUser;
use _\weixin\servPay;
use Core\unitResult;
use Student\servLesson;

class ctrlOrder extends ctrlSigned
{
    public function _DO_inquiry()
    {
        $sn = $this->apiGET('sn');
        $data = servOrders::sole($this->platform)->inquiry($sn, true);
        if ($data['uid'] == $this->uid) {
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['user not match']);
        }
    }

    public function _POST_book($type)
    {
        $sn = $this->apiPOST('sn');
        $originKey = $this->apiPOST('origin', '_');
        $servOrigin = servOrigin::sole($this->platform);

        if ($originKey) { // 存在有效来源，直接使用，并缓存
            $originId = $servOrigin->key2id($originKey);
            $servOrigin->cache($sn, $this->uid, $originId);
        } elseif (!$originId = $servOrigin->cache($sn, $this->uid, null)) { //尝试从缓存获取，失败
            $originId = servUser::sole($this->platform)->uid2origin($this->uid); //获取用户初始来源
        }

        switch ($type) {
            case 'lesson':
                $res = servLesson::sole($this->platform)->enroll($sn, $this->uid, $originId, null);
                if ($res['price'] ?? 0) {
                    $order = $res['order'];
                }
                break;
            case 'series':
                $res = servUnionOrder::sole($this->platform)->bookSeries($sn, $this->usn, $originId);
                if ($res['order_price']) {
                    $order = $res['order'];
                } else {
                    servOrders::sole($this->platform)->purchase($res['order']);
                }
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, ['illegal type']);
        }
        if ($res ?? null) {
            if ($order ?? null) {
                $data = servOrders::sole($this->platform)->inquiry($order ?? null, true);
            } else {
                $data = null;
            }
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['下单失败']);
        }

    }

    public function _POST_purchase()
    {
        $sn = $this->apiPOST('sn');
        $res = servOrders::sole($this->platform)->purchase($sn);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to purchase"]);
        }
    }


    public function _POST_prepay($way)
    {
        $sn = $this->apiPOST('sn');
        $useBalance = $this->apiPOST('useBalance', true);
        switch ($way) {
            case 'wxm':
                $result = servPay::sole($this->platform)->prepay(servOrders::PAY_WAY_WXM, $sn, $useBalance);
                break;
            case 'wxs':
                $result = servPay::sole($this->platform)->prepay(servOrders::PAY_WAY_WXS, $sn, $useBalance);
                break;
            case 'wxa':
                $result = servPay::sole($this->platform)->prepay(servOrders::PAY_WAY_WXA, $sn, $useBalance);
                break;
            default:
                $result = unitResult::inst();
                $result->err("Illegal action");
                break;
        }
        $this->apiResponse($result->error, $result->message, $result->data);
    }

    public function _GET_refund($type)
    {
        $sn = $this->apiGET('sn');

        $data = [
            'total' => [],
            'list' => [],
        ];
        switch ($type) {
            case 'lesson':
            case 'article':
            case 'column':
//                $data['mode'] = servLesson::sole($this->platform)->returnRefundMode($sn, $this->uid);
//                $items = servLesson::sole($this->platform)->sn2info($sn, ['title']);
//                $items = [];
                $items = servRefund::sole($this->platform)->prepareLesson($sn, $this->usn);
                break;
            case 'series':
                $items = servRefund::sole($this->platform)->prepareSeries($sn, $this->usn);
                break;
            default:
                $items = [];
        }
        foreach ($items as $item) {
            $payway = servOrder::PAY_WAY_MAP[$item['i_pay_way']];
            $data['total'][$payway] = ($data['total'][$payway] ?? 0) + $item['paid_amount']/100;
            $data['total']['balance'] = ($data['total']['balance'] ?? 0) + ($item['order_amount'] - $item['paid_amount'])/100;
            $data['list'][] = [
                'title' => $item['title'],
                'order_amount' => $item['order_amount']/100,
            ];
        }
        foreach ($data['total'] as &$v) {
            $v = round($v, 2);
        }
        $this->apiSuccess($data);
    }

    public function _POST_refund($type)
    {
        $sn = $this->apiPOST('sn');
        switch ($type) {
            case 'lesson':
            case 'article':
            case 'column':
                $items = servRefund::sole($this->platform)->refundLesson($sn, $this->usn, '无条件退款');
                break;
            case 'series':
                $items = servRefund::sole($this->platform)->refundSeries($sn, $this->usn, '无条件退款');
                break;
            default:
                $items = [];
        }
        $data = [];
        foreach ($items as $iWay => $item) {
            if ($iWay) {
                $data['weixin'] = ($data['weixin'] ?? 0) + $item/100;
            } else {
                $data['balance'] = $item / 100;
            }
        }
        servMpMsg::sole($this->platform)->sendRefundMessage($sn, $this->uid, $data, "无条件退款");
        $this->apiSuccess($data);
    }

    public function _POST_confirm($type)
    {
        $sn = $this->apiPOST('sn');
        $srvOrder = servOrder::sole($this->platform);
        switch ($type) {
            case 'lesson':
                $orderSn = $srvOrder->findPaidOrder($this->uid, $sn);
                if ($orderSn && $srvOrder->confirm($orderSn)) {
                    $data = [
                        'total' => 1,
                        'confirmed' => 1,
                    ];
                    $lessonId = servLesson::sole($this->platform)->sn2id($sn);
                    dataLessonAccess::sole($this->platform)->append($lessonId, $this->uid, dataLessonAccess::EVENT_CONFIRM, ['mark' => 'manual']);
                    $this->apiSuccess($data);
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ['Failed to confirm']);
                }
                break;
            case 'series':
                $lessons = servLessonSeries::sole($this->platform)->checkLessons($sn, $this->usn);
                $data = [
                    'total' => $lessons['lesson'],
                    'confirmed' => 0,
                ];
                foreach ($lessons['refund'] as $_lesson) {
                    $_orderSn = $srvOrder->findPaidOrder($this->uid, $_lesson['sn']);
                    if ($_orderSn && $srvOrder->confirm($_orderSn)) {
                        $data['confirmed'] ++;
                    }
                    $_lesson['id'] = servLesson::sole($this->platform)->sn2id($_lesson['sn']);
                    dataLessonAccess::sole($this->platform)->append($_lesson['id'], $this->uid, dataLessonAccess::EVENT_CONFIRM, ['mark' => 'manual']);
                }
                $this->apiSuccess($data);
                break;
        }
    }

    public function _POST_admire()
    {
        $sn = $this->apiPOST('sn');
        $amount = $this->apiPOST('amount');
        if ($amount < 100) {
            $this->apiFailure(self::ERR_UNDEFINED, ['金额不能小于1元']);
        }
        if ($amount && intval($amount) == $amount) {
            $lessonId = servLesson::sole($this->platform)->sn2id($sn);
            $orderSn = servOrder::sole($this->platform)->create($this->uid, $lessonId, Intval($amount), 0, [], dataOrder::TYPE_ADMIRE);
            $this->apiSuccess($orderSn);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['金额输入错误']);
        }
    }

    public function _GET_admired()
    {
        $sn = $this->apiGET('sn');
        $limit = $this->apiGET('limit');
        $data = servOrder::sole($this->platform)->admired($this->uid, $sn, intval($limit));
        $this->apiSuccess(array_values($data));
    }
}