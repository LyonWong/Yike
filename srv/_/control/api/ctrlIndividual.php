<?php


namespace _\api;


use _\dataLesson;
use _\dataLessonAccess;
use _\servLesson;
use _\servLessonSeries;
use _\servOrigin;
use _\servPromote;
use _\servUser;

class ctrlIndividual extends ctrlSigned
{
    public function _DO_lesson()
    {
        $lessonSn = $this->apiGET('sn');

        // 报名状态
        $srvLesson = servLesson::sole($this->platform);
        $srvPromote = servPromote::sole($this->platform);
        $daoAccess = dataLessonAccess::sole($this->platform);

        $lesson = $srvLesson->sn2info($lessonSn, ['id', 'price', 'i_form', 'category']);

        // 记录带标记的事件
        if ($mark = $this->apiGET('mark', '')) {
            dataLessonAccess::sole($this->platform)->append($lesson['id'], $this->uid, dataLessonAccess::EVENT_BROWSE, ['mark'=>$mark]);
        }

        $recent = servLesson::sole($this->platform)->recent($lessonSn, $this->uid, [
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_REFUND,
            dataLessonAccess::EVENT_RESET
        ]);
        // 退款
        if ($recent['refund_mode']) {
            $recent['refund_info']['mode'] = $recent['refund_mode'];
            $recent['refund_info']['countdown'] = $recent['refund_countdown'] ?? false;
            $refund = $recent['refund_info'];
        } else {
            $refund = false;
        }
        // 来源
        $res = $daoAccess->lastEventOnLesson($lesson['id'], $this->uid, [dataLessonAccess::EVENT_ENROLL]);
        // 无法直接获取来源时，尝试从上级获取
        if (empty($res['args']['origin']) && $lesson['category'] && $cid = $srvLesson->sn2id($lesson['category'])){
            $res = $daoAccess->lastEventOnLesson($cid, $this->uid, [dataLessonAccess::EVENT_ENROLL]);
        }
        if ($res['args']['origin'] ?? null) { // 有限使用直接报名来源
            $origin = servOrigin::sole($this->platform)->id2key($res['args']['origin']);
        }
        // 优惠券
        $psn = $srvPromote->check($this->uid, $lesson['id'], 0); // 检查已领取的
        if (!$psn) {
            // 根据推广渠道生成并领取
            $source = $this->apiGET('source', \input::cookie('source', '-')->value());
            list(, $promoteUSN) = explode('-', $source);
            if ($psn = $srvPromote->generate($lessonSn, $promoteUSN, $source)) {
                $srvPromote->draw($psn, $this->uid);
            }
        }
        if ($psn) {
            $pinfo = $srvPromote->attr($psn);
            $promote = [
                'sn' => $psn,
                'type' => $pinfo['type'],
                'from' => servUser::sole($this->platform)->uid2profile($pinfo['uid']),
                'discount' => $pinfo['discount'] / 100,
                'extime' => $srvPromote->extime($this->uid, $lesson['id'], 0)
            ];
        } else {
            $promote = null;
        }
        // 公众号关注
        $subscribe = servUser::sole($this->platform)->uid2info($this->uid, 'subscribe')['subscribe'];

        // 课程配置
        $conf = servLesson::sole($this->platform)->conf($lessonSn);

        $data = [
            'sn' => $this->usn,
            'status' => $recent['event'] ? $recent['event'] : 'fresh',
            'origin' => $origin ?? '',
            'refund' => $refund,
            'promote' => $promote,
            'subscribed' => boolval($subscribe),
            'zsxq' => $conf['zsxq'] ?? null
        ];
        $this->apiSuccess($data);
    }

    public function _DO_series()
    {
        $seriesSn = $this->apiGET('sn');

        $srvSeries = servLessonSeries::sole($this->platform);
        $srvPromote = servPromote::sole($this->platform);
        $seriesId = $srvSeries->sn2id($seriesSn);

        // 记录带标记的事件
        if ($mark = $this->apiGET('mark', '')) {
            dataLessonAccess::sole($this->platform)->append(0, $this->uid, dataLessonAccess::EVENT_BROWSE, ['series_id'=>$seriesId, 'mark'=>$mark]);
        }

        // 报名状态
        $data = $srvSeries->checkLessons($seriesSn, $this->usn);

        $psn = $srvPromote->check($this->uid, 0, $seriesId); // 检查已领取的
        if (!$psn) {
            // 根据推广渠道生成并领取
            $source = $this->apiGET('source', \input::cookie('source', '-')->value());
            list(, $promoteUSN) = explode('-', $source);
            if ($psn = $srvPromote->generate($seriesSn, $promoteUSN, $source)) {
                $srvPromote->draw($psn, $this->uid);
            }
        }
        if ($psn) {
            $pinfo = $srvPromote->attr($psn);
            $promote = [
                'sn' => $psn,
                'from' => servUser::sole($this->platform)->uid2profile($pinfo['uid']),
                'discount' => $pinfo['discount'] / 100,
                'extime' => $srvPromote->extime($this->uid, 0, $seriesId)
            ];
        } else {
            $promote = null;
        }

        $data['promote'] = $promote;

        // 公众号关注
        $data['subscribed'] = (bool)servUser::sole($this->platform)->uid2info($this->uid, 'subscribe')['subscribe'];

        $conf = servLessonSeries::sole($this->platform)->conf($seriesSn);

        $data['zsxq'] = $conf['zsxq'] ?? null;

        $data['sn'] = $this->usn;

        $this->apiSuccess($data);
    }

}