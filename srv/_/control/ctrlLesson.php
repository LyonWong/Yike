<?php


namespace _;


class ctrlLesson extends ctrlSess
{
    const ERR_LESSON_NOT_AVAILABLE = ['1', 'not available'];
    const ERR_ENROLLED_BEFORE = ['2', 'enrolled before'];
    const ERR_ACCESS_DENIED = ['1', 'access denied'];

    const ERR_ILLEGAL_REFUND_MODE = ['1', 'illegal refund mode `%s`'];
    const ERROR_REFUND_FAILED = ['2', 'failed to refund'];
    const ERR_UNABLE_TO_REFUND = ['3', 'unable to refund `%s'];
    const ERR_UNABLE_TO_REFUND_APPLY = ['4', 'unable to refund apply'];
    const ERR_UNABLE_TO_REFUND_APPEAL = ['5', 'unable to refund appeal'];

    const ERR_CANNOT_RATE_BEFORE_ACCESS = ['1', 'cannot rate before access'];
    const ERR_CANNOT_RATE_AGAIN = ['2', 'cannot rate again'];
    const ERROR_CANNOT_RATE_REFUND_APPLY = ['3', 'CANNOT rate after refund apply'];

    public function _DO_profile()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $data = servLesson::sole($this->platform)->profile($lessonSn);
        $this->apiSuccess($data);
    }

    public function _DO_detail()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $srvCache = servCache::sole($this->platform);

        $rkey = servCache::TAG_LESSON_DETAIL.$lessonSn;
        if ( ($data = $srvCache->getJson($rkey)) === null ) {
            $data = servLesson::sole($this->platform)->detail($lessonSn);
            $srvCache->setJson($rkey, $data, SECONDS_MINUTE);
        }
        /*
        $data['rated'] = servRating::sole($this->platform)->rated($lessonSn, $this->uid);
        $recent = $servLesson->recent($lessonSn, $this->uid);
        $data['event'] = $recent['event'];
        $data['refund_mode'] = $recent['refund_mode'];
        $data['refund_info'] = $recent['refund_info'];
        */
        $this->apiSuccess($data);
    }

    public function _POST_repose()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        servLesson::sole($this->platform)->repose($lessonSn, $this->uid);
        $this->apiSuccess();
    }

    public function _POST_finish()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        servLesson::sole($this->platform)->finish($lessonSn, $this->uid);
        $this->apiSuccess();
    }

    public function _GET_audio_draft()
    {
        $key = uniqid('draft/audio/');
        $token = servQiniu::inst()->getUploadToken($key, [
            'deleteAfterDays' => 1,
            'persistentOps' => 'avthumb/mp3',
            'fsizeLimit' => 100 * servQiniu::SIZE_MB
        ]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
        ];
        $this->apiSuccess($data);
    }

    public function _POST_refund($mode)
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $servLesson = servLesson::sole($this->platform);
        $checkMode = $servLesson->returnRefundMode($lessonSn, $this->uid);
        switch ($mode) {
            case servLesson::REFUND_MODE_FREELY: // 限时自由退款
                if ($mode == $checkMode) {
                    $res = $servLesson->refund($lessonSn, $this->uid);
                    if ($res) {
                        $servLesson->getRidOf($this->usn, $lessonSn);
                        servMpMsg::sole($this->platform)->sendRefundMsg($lessonSn, $this->uid);
                        servRating::sole($this->platform)->hide($lessonSn, $this->uid);
                        $this->apiSuccess();
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
                    servRating::sole($this->platform)->hide($lessonSn, $this->uid);
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_UNABLE_TO_REFUND_APPLY);
                }
                break;
            case 'appeal': // 向平台申诉退款
                $reason = $this->apiPOST('reason');
                $res = servRefund::sole($this->platform)->appeal($this->uid, $lessonSn, $reason);
                if ($res) {
                    servRating::sole($this->platform)->hide($lessonSn, $this->uid);
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
        $score = (int)$this->apiPOST('score');
        $remark = $this->apiPOST('remark', '');
        $lessonSn = $this->apiPOST('lesson_sn');
        if (!servLesson::sole($this->platform)->hasEvent($lessonSn, $this->uid, dataLessonAccess::EVENT_ACCESS)) {
            $this->apiFailure(self::ERR_CANNOT_RATE_BEFORE_ACCESS);
        }
        if (servLesson::sole($this->platform)->hasRefundApply($lessonSn, $this->uid)) {
            $this->apiFailure(self::ERROR_CANNOT_RATE_REFUND_APPLY);
        }
        $ret = \_\servLesson::sole($this->platform)->lastLessonIevent($lessonSn, $this->uid);
        $lastEvent = $ret[0]['i_event'] ?? 0;
        if ($lastEvent < dataLessonAccess::EVENT_RESET) {
            $this->apiFailure(self::ERROR_CANNOT_RATE_REFUND_APPLY);
        }
        if (servRating::sole($this->platform)->rated($lessonSn, $this->uid)) {
            $this->apiFailure(self::ERR_CANNOT_RATE_AGAIN);
        }
        $ret = servRating::sole($this->platform)->rate($score, $remark, $lessonSn, $this->uid);
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);

    }
}