<?php

namespace Student;

use _\dataLessonAccess;
use _\dataRating;
use _\servCache;
use _\servLessonBoard;
use _\servMpMsg;
use _\servPromote;
use _\servRating;
use _\servRefund;
use _\servRelation;
use _\weixin\servMp;
use Core\unitAPI;
use Core\unitDoAction;
use _\servOrigin;

class ctrlLesson extends ctrlSess
{
    use unitAPI;
    use unitDoAction;

    const ERR_LESSON_NOT_AVAILABLE = ['1', 'not available'];
    const ERR_ENROLLED_BEFORE = ['2', 'enrolled before'];
    const ERR_ACCESS_DENIED = ['1', 'access denied'];

    const ERR_ILLEGAL_REFUND_MODE = ['1', 'illegal refund mode `%s`'];
    const ERROR_REFUND_FAILED = ['2', 'failed to refund'];
    const ERR_UNABLE_TO_REFUND = ['3', 'unable to refund `%s'];
    const ERR_UNABLE_TO_REFUND_APPLY = ['4', 'unable to refund apply'];
    const ERR_UNABLE_TO_REFUND_APPEAL = ['5', 'unable to refund appeal'];


    public function _GET_profile()
    {
        $srvCache = servCache::sole($this->platform);
        $lessonSn = $this->apiGET('lesson_sn');
        $ckey = servCache::TAG_LESSON_PROFILE . $lessonSn;
        if (($res = $srvCache->getJson($ckey)) === null) {
            $res = servLesson::sole($this->platform)->profile($lessonSn);
            $srvCache->setJson($ckey, $res, SECONDS_MINUTE);
        }
        $this->apiSuccess($res);
    }

    public function _GET_conf()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $conf = servLesson::sole($this->platform)->conf($lessonSn);
        $this->apiSuccess($conf);
    }

    public function _GET_list()
    {
        $srvCache = servCache::sole($this->platform);
        $params = $this->apiRequest(null);
        $lessonQuery = unitLessonQuery::inst($params);
        $ckey = servCache::TAG_LESSON_LIST . $lessonQuery->toString();
        if (($list = $srvCache->getJson($ckey)) === null) {
            $list = servLesson::sole($this->platform)->list($this->uid, $lessonQuery);
            $srvCache->setJson($ckey, $list, SECONDS_MINUTE);
        }
        $this->apiSuccess($list);
    }

    public function _GET_auto()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $step = servLesson::sole($this->platform)->step($lessonSn);
        $liveDomain = \config::load('boot', 'public', 'domain');
        $access = servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn);
        if ($step > dataLesson::STEP_OPENED && $access) {
            $liveUrl = $_SERVER['REQUEST_SCHEME']. '://' . $liveDomain . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D&teacherEnter=yes#/";
            $this->httpLocation($liveUrl);
        } else {
            $detailUrl = '/?v=1#/course/detail/brief?lesson_sn='.$lessonSn;
            $this->httpLocation($detailUrl);
        }
    }

    public function _GET_detail()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $servLesson = servLesson::sole($this->platform);
        $srvCache = servCache::sole($this->platform);
        $ckeyd = servCache::TAG_LESSON_DETAIL . $lessonSn;
        if (($detail = $srvCache->getJson($ckeyd)) === null) {
            $detail = $servLesson->detail($lessonSn);
            $srvCache->setJson($ckeyd, $detail, SECONDS_MINUTE);
        }
//        $detail['rated'] = servRating::sole($this->platform)->rated($lessonSn, $this->uid);
//        $detail['rated_list'] = servRating::sole($this->platform)->rating($lessonSn, 0, 3, dataRating::TOWARD_PREVIOUS);
//        $detail['rated_count'] = servRating::sole($this->platform)->rateCount($lessonSn);
        $categoryCheck = $detail['extra']['category_check'] ?? 0;
        if (!$categoryCheck) {
            $detail['category'] = "";
            $detail['categoryInfo'] = false;
        }
        $ckeye = servCache::TAG_LESSON_EXTRA . $lessonSn;
        if ( ($extra = $srvCache->getJson($ckeye)) === null) {
            $extra = [
                'series' => $detail['category'] ? servLessonSeries::sole($this->platform)->listLesson($detail['category'], true, $lessonSn) : false,
                'conf' => servLesson::sole($this->platform)->conf($lessonSn),
            ];
            $srvCache->setJson($ckeye, $extra, SECONDS_MINUTE);
        }

        $detail['series'] = $extra['series'];
        $detail['conf'] = $extra['conf'];

        //personal
//        $servLesson->browse($lessonSn, $this->uid); //personal
        //personal
        $detail['teacher']['isFollow'] = servRelation::sole($this->platform)->isFollow($this->uid, servUser::sole($this->platform)->usn2uid($detail['teacher']['sn']));
        //personal
        $recent = $servLesson->recent($lessonSn, $this->uid, [
            dataLessonAccess::EVENT_BROWSE,
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_LEAVE,
            dataLessonAccess::EVENT_RESET,
            dataLessonAccess::EVENT_CANCEL,
            dataLessonAccess::EVENT_REFUND
        ]);
        $detail['event'] = $recent['event'] ?: servLesson::ACCESS_MAP[dataLessonAccess::EVENT_RESET];
        $detail['refund_mode'] = $recent['refund_mode'];
        $detail['refund_info'] = $recent['refund_info'];
//        \view::tpl("/lesson/detail");
        $this->apiSuccess($detail);
    }

    /**
     * 课堂报名
     */
    public function _POST_enroll()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $type = $this->apiPOST('type', 'mp');
        $originKey = $this->apiPOST('origin', '');
        $orderId = intval($this->apiPOST('order_id', 0));
        $servUser = servUser::sole($this->platform);
        $servOrigin = servOrigin::sole($this->platform);
        $servLesson = servLesson::sole($this->platform);
        $lessonInfo = $servLesson->sn2info($lessonSn, ['id', 'tuid', 'title']);

        if (!$servLesson->isAvailable($lessonSn)) {
            $this->apiFailure(self::ERR_LESSON_NOT_AVAILABLE);
        }
        if ($servLesson->isEnrolled($lessonInfo['id'], $this->uid)) {
            $this->apiFailure(self::ERR_ENROLLED_BEFORE);
        }

        if ($originKey) { // 存在有效来源，直接使用，并缓存
            $originId = $servOrigin->key2id($originKey);
            $servOrigin->cache($lessonSn, $this->uid, $originId);
        } elseif (!$originId = $servOrigin->cache($lessonSn, $this->uid, null)) { //尝试从缓存获取，失败
            $originId = $servUser->uid2origin($this->uid); //获取用户初始来源
        }

        if ($res = $servLesson->enroll($lessonSn, $this->uid, $originId, $orderId)) {
            $res['subscribed'] = servMp::sole($this->platform)->isSubscribe($this->uid);
            if ($res['price'] > 0 && $res['margin'] < 0) { // 余额不足以支付报名费
                $title = $servLesson->sn2title($lessonSn);
                if ($type == 'native') {
                    $res['pay_data'] = pay\servOrder::sole($this->platform)->createNativePayData($this->uid, $res['order'], $title, -$res['margin'] * 100);
                    if ($res['pay_data']) {
                        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
                        $res['pay_url'] = "$_SERVER[REQUEST_SCHEME]://" . $domain
                            . '/pay/order-codeUrl?url=' . base64_encode($res['pay_data']);
                        unset($res['pay_data']);
                    }
                } else {
                    $res['pay_data'] = pay\servOrder::sole($this->platform)->createJsPayData($this->uid, $res['order'], $title, -$res['margin'] * 100);
                }
            } elseif ($res['price'] == 0) {
                servMpMsg::sole($this->platform)->sendEnrollMsg($lessonSn, $this->uid);
            }
            //报名成功返回价格订单号，免费课程订单字段为null,付费课程另外有pay_data,margin>=0表示余额足够，否则需要另行支付
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to enroll"]);
        }
    }

    /**
     * 使用账户余额付款
     */
    public function _POST_purchase()
    {
        $orderSn = $this->apiPOST('order');
        $res = servOrder::sole($this->platform)->purchase($orderSn, 0);
        if ($res) {
            $order = dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], ['uid', 'lesson_id']);
            $lessonSn = servLesson::sole($this->platform)->id2sn($order['lesson_id']);
            servPromote::sole($this->platform)->sendPromoteMsg($order['uid'], $lessonSn);
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to purchase"]);
        }
    }

    /**
     * 进入课堂
     */
    public function _GET_access()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $srvLesson = servLesson::sole($this->platform);
        $res = $srvLesson->checkAccess($lessonSn, $this->usn);
        if ($res) {
            $room = $srvLesson->sn2room($lessonSn);
            $this->apiSuccess($room);
        } else {
            $this->apiFailure(self::ERR_ACCESS_DENIED);
        }
    }

    /**
     * 确认课程，放弃退款
     */
    public function _POST_confirm()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $res = servLesson::sole($this->platform)->confirm($lessonSn, $this->uid);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['failed']);
        }
    }

    /**
     * 取消课程
     */
    public function _POST_cancel()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $res = servLesson::sole($this->platform)->cancel($lessonSn, $this->uid);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['failed']);
        }
    }

    public function _POST_refund($mode)
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $servLesson = servLesson::sole($this->platform);
        $lessonId = $servLesson->sn2id($lessonSn);
        $checkMode = $servLesson->returnRefundMode($lessonSn, $this->uid);
        //只要申请退款，就隐藏评论
        servRating::sole($this->platform)->hide($lessonSn, $this->uid);
        //只要申请退款，就隐藏留言
        servLessonBoard::sole($this->platform)->hide($lessonSn, $this->uid);
        $orderSn = servOrder::sole($this->platform)->findPaidOrder($this->uid, $lessonSn);
        if (!$orderSn) {
            $this->apiFailure(self::ERR_UNDEFINED, ["无可退款订单"]);
        }
        $order = servOrder::sole($this->platform)->detail($orderSn);
        switch ($mode) {
            case servLesson::REFUND_MODE_FREELY: // 限时自由退款
                if ($mode == $checkMode) {
                    $res = $servLesson->refund($lessonSn, $this->uid);
                    if ($res) {
                        $servLesson->getRidOf($this->usn, $lessonSn);
                        if ($order['order']['order_amount'] == 0) {
                            $this->apiFailure(self::ERR_UNDEFINED, ["未能完成自动退款，请通过【建议反馈】功能告知我们支付方式及订单号"]);
                        } else {
                            servMpMsg::sole($this->platform)->sendRefundMsg($lessonSn, $this->uid);
                            if (!$servLesson->hasEvent($lessonSn, $this->uid, dataLessonAccess::EVENT_ACCESS)) {
                                dataLessonAccess::sole($this->platform)->append($lessonId, $this->uid, dataLessonAccess::EVENT_RESET, ['reason' => 'no access free']);
                            }
                            $this->apiSuccess();
                        }
                    } else {
                        $this->apiFailure(self::ERROR_REFUND_FAILED);
                    }
                } else {
                    $this->apiFailure(self::ERR_UNABLE_TO_REFUND, [$mode]);
                }
                break;
            case 'apply': // 向讲师申请退款
                $reason = $this->apiPOST('reason');
                $res = servRefund::sole($this->platform)->apply($this->uid, $lessonSn, $reason);
                if ($res) {
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_UNABLE_TO_REFUND_APPLY);
                }
                break;
            case 'appeal': // 向平台申诉退款
                $reason = $this->apiPOST('reason');
                $res = servRefund::sole($this->platform)->appeal($this->uid, $lessonSn, $reason);
                if ($res) {
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_UNABLE_TO_REFUND_APPEAL);
                }
                break;
            default:
                $this->apiFailure(self::ERR_ILLEGAL_REFUND_MODE, [$mode]);
                break;
        }
    }

    public function _POST_rating()
    {
        //todo 注意platform等属性的缺失
        $ctrl = new \_\ctrlLesson();
        $ctrl->uid = $this->uid;
        $ctrl->platform = $this->platform;
        $ctrl->_POST_rating();
    }

    public function _GET_rating($item)
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $cursor = (int)$this->apiGET('cursor', 0);
        $limit = (int)$this->apiGET('limit', 10);
        $toward = $this->apiGET('toward', 'previous');
        $toward = servRating::TOWARD_MAP[$toward] ?? dataRating::TOWARD_PREVIOUS;
        $srvCache = servCache::sole($this->platform);
        switch ($item) {
            case 'list':
                $ckey = servCache::TAG_LESSON_RATE_LIST . "$lessonSn|$cursor|$limit|$toward";
                if (($data = $srvCache->getJson($ckey)) === null) {
                    $data = servRating::sole($this->platform)->rating($lessonSn, $cursor, $limit, $toward);
                    $srvCache->setJson($ckey, $data, SECONDS_MINUTE);
                }
                break;
            case 'self':
                $data = servRating::sole($this->platform)->rated($lessonSn, $this->uid);
                break;
            case 'count':
                $data = servRating::sole($this->platform)->rateCount($lessonSn);
                $data['rated'] = servRating::sole($this->platform)->rated($lessonSn, $this->uid);
                break;
            default:
                $data = null;
        }

        $this->apiSuccess($data);
    }

    public function _GET_history()
    {
        $history = servLesson::sole($this->platform)->history($this->uid);
        $this->apiSuccess($history);
    }

    public function _GET_order()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $order = servOrder::sole($this->platform)->findOrderByLessonId($this->uid, $lessonId);
        $this->apiSuccess($order);

    }

    public function _GET_script()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $ret = \_\servLesson::sole($this->platform)->lastLessonIevent($lessonSn, $this->uid);
        $force = $this->apiGET('force', 0);
        $lastEvent = $ret[0]['i_event'] ?? 0;
        $type = 1;
//        $lastEvent = 3;
        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
        if ($lastEvent < dataLessonAccess::EVENT_ENROLL) {
            //未报名
            $urls = [
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/weixin-lesson?lesson_sn=' . $lessonSn
            ];
        } elseif ($lastEvent == dataLessonAccess::EVENT_ENROLL) {
            //报名未进课堂
            $type = 2;
            $urls = [
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/lesson-script?lesson_sn=' . $lessonSn . '&force=1',
                "$_SERVER[REQUEST_SCHEME]://" . $domain . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D#/"
            ];
            if ($force) {
                \_\servLesson::sole($this->platform)->access($lessonSn, $this->uid);
                \view::tpl('/lesson/draft-sc-' . $lessonSn, [
                    'url' => "$_SERVER[REQUEST_SCHEME]://" . $domain . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D#/"

                ]);
                exit;
            }
        } else {
            //直接进
            \view::tpl('/lesson/draft-sc-' . $lessonSn, [
                'url' => "$_SERVER[REQUEST_SCHEME]://" . $domain . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D#/"
            ]);
            exit;
        }

        \view::tpl('/lesson/draft', [
            'type' => $type,
            'url' => $urls
        ]);
    }

    public function _DO_script_sc()
    {
        $domain = \config::load('boot', 'public', 'domain', '', 'Student');

        \view::tpl('/lesson/draft-sc', [
            'url' => [
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/lesson-script?lesson_sn=L5a02a65fb4995',
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/lesson-script?lesson_sn=L5a02a969a1d46',
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/lesson-script?lesson_sn=L59ffe3e32d3c3',
                "$_SERVER[REQUEST_SCHEME]://" . $domain . '/lesson-script?lesson_sn=L5a030660af352',
            ]
        ]);
    }
}