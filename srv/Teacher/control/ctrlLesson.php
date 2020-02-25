<?php


namespace Teacher;

use _\config;
use _\dataLessonProcess;
use _\dataRating;
use _\servLessonSeries;
use _\servRating;
use _\servQiniu;
use _\servTicket;
use Core\unitAPI;

class ctrlLesson extends ctrlSess
{

    const ERR_NOT_YOUR_LESSON = [1, 'not your lesson'];
    const ERR_TOO_EARLY_TO_OPEN = [2, "too early to open"];
    const ERR_UNABLE_TO_OPEN = [3, "unable to open"];
    const ERR_NOT_YOUR_SERIES = [4, '邀请token错误或已失效'];


    public function _DO_()
    {
        $list = servLesson::sole($this->platform)->getList($this->uid);
        \view::tpl('page', [
            'page' => 'lesson',
            'list' => $list,
        ]);
    }

    public function _DO_live($sn)
    {
        $res = servLesson::sole($this->platform)->open($sn);
        if ($res) {
            echo "直播间：$sn-T";
        } else {
            echo "创建直播间失败";
        }
    }

    public function _DO_create()
    {
        \view::tpl('page', [
            'page' => 'lesson-create'
        ]);
    }

    public function _GET_cover_draft()
    {
        $key = uniqid('draft/cover/');
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1, 'fsizeLimit' => 100 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
        ];
        $this->apiSuccess($data);
    }

    public function _GET_content_url()
    {
        $key = uniqid('lesson/content/');
        $token = servQiniu::inst()->getUploadToken($key, ['fsizeLimit' => 100 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
            'url' => \view::upload($key)
        ];
        $this->apiSuccess($data);
    }


    public function _GET_audio_draft()
    {
        $key = uniqid('draft/audio/');
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1, 'fsizeLimit' => 100 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
        ];
        $this->apiSuccess($data);
    }

    public function _POST_create()
    {
        $unitLesson = new \_\unitLesson();
        $unitLesson->title = $this->apiPOST('title');
        $unitLesson->brief = $this->apiPOST('brief');
        $unitLesson->category = $this->apiPOST('category', '');
        $form = $this->apiPOST('form', servLesson::FORM_MAP[dataLesson::FORM_IM]);
        $unitLesson->price = $this->apiPOST('price', 0) * 100;
        $unitLesson->quota = $this->apiPOST('quota', 0);
        $unitLesson->dtmStart = $this->apiPOST('dtm_start');
        $unitLesson->duration = $this->apiPOST('duration');
        $unitLesson->cover = $this->apiPOST('cover', '');

        $unitLesson->iForm = array_search($form, servLesson::FORM_MAP);

        $isPublic = $this->apiPOST('isPublic', 1);
        if (!$isPublic) {
            $unitLesson->iForm = -$unitLesson->iForm;
        }

        //系列检测权限
        $token = $this->apiPOST('token', '');
        if ($unitLesson->category && !servLessonSeries::sole($this->platform)->check($this->uid, $unitLesson->category, $token)) {
            $this->apiFailure(self::ERR_NOT_YOUR_SERIES);
        }
        $srvLesson = servLesson::sole($this->platform);
        $lessonSn = $srvLesson->create($this->uid, $unitLesson);
        if (servTeacher::sole($this->platform)->status($this->uid) == dataTeacher::STATUS_CREDIBLE) {
            $srvLesson->reviewCreate($lessonSn);
        }
        if ($lessonSn) {
            $this->apiSuccess(['lesson_sn' => $lessonSn]);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }

    public function _POST_modify()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $params = $this->apiRequest(null);
        $status = \_\servTeacher::sole($this->platform)->status($this->uid);
        $review = ($status == \_\dataTeacher::STATUS_CREDIBLE) ? false : true;
        $ret = servLesson::sole($this->platform)->modify($lessonSn, $params, $this->uid,$review);
        if ($ret) {
            $this->apiSuccess($params);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }

    }

    public function _GET_detail()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $detail = servLesson::sole($this->platform)->detail($lessonSn);
        $detail['stats'] = \_\stats\servLesson::sole($this->platform)->getSummary($lessonId);
        $categoryCheck = $detail['extra']['category_check'] ?? 0;
        if (!$categoryCheck) {
            $detail['category'] = "";
            $detail['categoryInfo'] = false;
        }
        $detail['series'] = $detail['category'] ? servLessonSeries::sole($this->platform)->listLesson($detail['category'], true, $lessonSn) : false;
        $this->apiSuccess($detail);
    }

    public function _GET_edit()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $detail = servLesson::sole($this->platform)->detail($lessonSn);
        $review = servTicket::sole($this->platform)->lessonInReview($this->uid, $lessonSn);
        $data = arrayMergeForce($detail, $review['content']??[]);
        $data['review_status'] = $review['review_status'];
        $this->apiSuccess($data);
    }

    public function _POST_open()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $profile = servLesson::sole($this->platform)->profile($lessonSn);
        if ($this->usn != $profile['teacher']['sn']) {
            $this->apiFailure(self::ERR_NOT_YOUR_LESSON);
        }
        if (strtotime("{$profile['plan']['dtm_start']} - 15 minutes") > time()) {
            $this->apiFailure(self::ERR_TOO_EARLY_TO_OPEN);
        }
        if (!in_array($profile['step'], [
            servLesson::STEP_MAP[dataLessonProcess::EVENT_OPENED],
            servLesson::STEP_MAP[dataLessonProcess::EVENT_ONLIVE],
            servLesson::STEP_MAP[dataLessonProcess::EVENT_REPOSE],
            servLesson::STEP_MAP[dataLessonProcess::EVENT_FINISH]
        ])
        ) {
            $this->apiFailure(self::ERR_UNABLE_TO_OPEN);
        };
        $res = servLesson::sole($this->platform)->open($lessonSn);
        if ($res === true) {
            $data = [
                'teach' => "$lessonSn-T",
                'discuss' => "$lessonSn-D"
            ];
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, [$res]);
        }
    }

    public function _POST_repose()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $res = servLesson::sole($this->platform)->repose($lessonSn, $this->uid);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }

    public function _POST_finish()
    {
    }

    public function _POST_close()
    {
    }

    public function _GET_list()
    {
        $list = servLesson::sole($this->platform)->getList($this->uid);
        $this->apiSuccess($list);
    }

    public function _GET_rating($item)
    {
        $limit = intval($this->apiGET('limit', 10));
        $page = intval($this->apiGET('page', 1));
        $lessonSn = $this->apiGET('lesson_sn');
        switch ($item) {
            case 'list':
                $data = servRating::sole($this->platform)->ratingList($lessonSn, $limit, $page);
                break;
            default:
                $data = null;
        }
        $this->apiSuccess($data);
    }

    public function _POST_rating($action)
    {
        switch ($action) {
            case 'reply':
                $cursor = $this->apiPOST('cursor');
                $text = $this->apiPOST('text');
                $res = servRating::sole($this->platform)->reply($cursor, $text, $this->uid);
                if ($res == true) {
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ["failed to reply `$cursor`"]);
                }
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, ["illegal action `$action`"]);
                break;
        }

    }

}