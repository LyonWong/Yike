<?php


namespace Student;


use _\config;
use _\dataUnionOrder;
use _\servCache;
use _\servLessonSeries;
use _\servOrigin;
use _\servPromote;
use _\servUnionOrder;
use _\weixin\servMp;

class ctrlSeries extends ctrlSess
{
    const ERR_NO_LESSON_TO_PURCHASE = ['1', 'no lesson to purchase'];
    const ERR_ORDER_SN = ['2', 'error order sn'];

    public function _GET_conf()
    {
        $seriesSn = $this->apiGET('series_sn');
        $conf = servLessonSeries::sole($this->platform)->conf($seriesSn);
        $this->apiSuccess($conf);
    }

    public function _GET_detail()
    {
        $seriesSn = $this->apiGET('series_sn');
        $srvCache = servCache::sole($this->platform);
        $rkey = servCache::TAG_LESSON_DETAIL.$seriesSn;
        if ( ($detail = $srvCache->getJson($rkey)) === null ) {
            $detail = servLessonSeries::sole($this->platform)->detail($seriesSn);
            $detail['lesson-list'] = servLessonSeries::sole($this->platform)->listLesson($seriesSn, true);
            $detail['show_card'] = $detail['sn'] == 'S59ffcaacd9c4d' ? true : false;
            $detail['conf'] = servLessonSeries::sole($this->platform)->conf($seriesSn);
            $srvCache->setJson($rkey, $detail, SECONDS_MINUTE);
        }
        $detail['purchase_check'] = count(servUnionOrder::sole($this->platform)->checkLessons($seriesSn, $this->usn));
        $this->apiSuccess($detail);
    }

    public function _GET_listLesson()
    {
        $seriesSn = $this->apiGET('series_sn');
        $list = servLessonSeries::sole($this->platform)->listLesson($seriesSn, true);
        $this->apiSuccess($list);
    }

    public function _GET_auto()
    {
        $seriesSn = $this->apiGET('series_sn');
        $list = servLessonSeries::sole($this->platform)->listLesson($seriesSn, true);
        $liveDomain = config::load('boot', 'public', 'domain');
        $seriesUrl = '/weixin-series?series_sn='.$seriesSn;
        foreach ($list as $lesson) {
            $iStep = array_search($lesson['step'], servLesson::STEP_MAP);
            $access = servLesson::sole($this->platform)->checkAccess($lesson['sn'], $this->usn);
            $liveUrl = $_SERVER['REQUEST_SCHEME']. '://' . $liveDomain . "/live?isOwner=no&lesson_sn=$lesson[sn]&teach=$lesson[sn]-T&discuss=$lesson[sn]-D&teacherEnter=yes#/";
            if ($iStep > dataLesson::STEP_OPENED && $access) {
                $this->httpLocation($liveUrl);
                exit;
            }
        }
        $this->httpLocation($seriesUrl);
    }

    public function _POST_check()
    {
        $seriesSn = $this->apiPOST('series_sn');
        $lessons = servUnionOrder::sole($this->platform)->checkLessons($seriesSn, $this->usn);
        if (count($lessons)) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_NO_LESSON_TO_PURCHASE);
        }
    }

    public function _POST_order()
    {
        $seriesSn = $this->apiPOST('series_sn');
        $originKey = $this->apiPOST('origin', '');

        $srvOrigin = servOrigin::sole($this->platform);
        $srvUser = servUser::sole($this->platform);

        if ($originKey) { // 存在有效来源，直接使用，并缓存
            $originId = $srvOrigin->key2id($originKey);
            $srvOrigin->cache($seriesSn, $this->uid, $originId);
        } elseif (!$originId = $srvOrigin->cache($seriesSn, $this->uid, null)) { //尝试从缓存获取，失败
            $originId = $srvUser->uid2origin($this->uid); //获取用户初始来源
        }
        $res = servUnionOrder::sole($this->platform)->bookSeries($seriesSn, $this->usn, $originId);
        if (count($res['lessons'])) {
            $res['subscribed'] = servMp::sole($this->platform)->isSubscribe($this->uid);
            if ($res['surplus']) { // 余额不足
                $res['pay_data'] = pay\servOrder::sole($this->platform)->createJsPayData($this->uid, $res['order'], $res['series_name'], $res['surplus'] * 100);
            }
            $this->apiSuccess($res);

        } else {
            $this->apiFailure(self::ERR_NO_LESSON_TO_PURCHASE);
        }
    }

    public function _POST_purchase()
    {
        $order = $this->apiPOST('order');
        $res = servUnionOrder::sole($this->platform)->purchase($order, 0);
        if ($res) {
            $orderInfo = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $order], ['uid', 'extra']);
            servPromote::sole($this->platform)->sendPromoteMsg($orderInfo['uid'], $orderInfo['extra']['series_sn']);
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to purchase"]);
        }

    }


}