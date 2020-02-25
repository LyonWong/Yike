<?php


namespace Teacher;

use _\config;
use _\servLessonSeries;
use _\servQiniu;
use _\servTicket;
use _\unitIntroduce;
use _\unitLessonSeriesScheme;
use Core\library\Http;

class ctrlSeries extends ctrlSess
{

    const ERR_UNABLE_TO_CHECK = [4, "系列课价格不能超过所选课程单价总额"];


    public function _DO_list()
    {
        $status = $this->apiGET('status', '');
        $seriesSn = $this->apiGET('series_sn', '');

        $list = servLessonSeries::sole($this->platform)->listByUid($this->uid, $status, $seriesSn);
        $this->apiSuccess($list);
    }

    public function _GET_listLesson()
    {
        $seriesSn = $this->apiGET('series_sn');
        $list = servLessonSeries::sole($this->platform)->listLesson($seriesSn);
        $this->apiSuccess($list);
    }

    public function _GET_detail()
    {
        $seriesSn = $this->apiGET('series_sn');
        $detail = servLessonSeries::sole($this->platform)->detail($seriesSn);
        $this->apiSuccess($detail);
    }


    public function _POST_create()
    {
        $unitLessonSeriesScheme = new unitLessonSeriesScheme();
        $type = $this->apiPOST('type', unitIntroduce::TYPE_TEXT);
        $content = $this->apiPOST('content');
        $cover = $this->apiPOST('cover', '');
        $unitIntroduce = new unitIntroduce($type, $content, $cover);
        $name = $this->apiPOST('name');
        $unitLessonSeriesScheme->price = $this->apiPOST('price') * 100 ?: null; //null表示自动使用单课累加价格
        $unitLessonSeriesScheme->share = intval($this->apiPOST('share', 100));

        $srvSeries = servLessonSeries::sole($this->platform);
        $seriesSn = $srvSeries->create($this->uid, $name, $unitIntroduce, $unitLessonSeriesScheme);
        if (servTeacher::sole($this->platform)->status($this->uid) == dataTeacher::STATUS_CREDIBLE) {
            $srvSeries->reviewCreate($seriesSn);
        }
        if ($seriesSn) {
            $this->apiSuccess(['series_sn' => $seriesSn]);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }

    }

    /**
     * @param series_sn string 系列课串码
     * @param lesson_sns string 勾选的课程串码，用逗号连起来
     * @param price int
     */
    public function _POST_check()
    {
        $seriesSn = $this->apiPOST('series_sn');
        $lessonSns = $this->apiPOST('lesson_sns');
        $price = $this->apiPOST('price', 0) * 100;
        $lessonSns = $lessonSns ? explode(',', $lessonSns) : '';
        if ($lessonSns) {
            $sum = servLessonSeries::sole($this->platform)->sumCheck($lessonSns);
            if ($sum < $price) {
                $this->apiFailure(self::ERR_UNABLE_TO_CHECK);
            }
        }
        $ret = servLessonSeries::sole($this->platform)->updateCheck($seriesSn, $lessonSns, $price);
        if ($ret) {
            $this->apiSuccess($ret);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }

    public function _POST_modify()
    {
        $seriesSn = $this->apiPOST('series_sn');
        $params = $this->apiRequest(null);
        $status = \_\servTeacher::sole($this->platform)->status($this->uid);
        $review = ($status == \_\dataTeacher::STATUS_CREDIBLE) ? false : true;
        $ret = servLessonSeries::sole($this->platform)->modify2($seriesSn, $params, $this->uid, $review);
        if ($ret) {
            $this->apiSuccess($ret);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }

    }

    public function _GET_edit()
    {
        $seriesSn = $this->apiGET('series_sn');
        $detail = servLessonSeries::sole($this->platform)->detail($seriesSn);
        $review = servTicket::sole($this->platform)->seriesInReview($this->uid, $seriesSn);
        $data = arrayMergeForce($detail, $review ?? []);
        $data['review_status'] = $review['review_status'];
        $this->apiSuccess($data);
    }

    public function _GET_draft()
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
        $key = uniqid('series/content/');
        $token = servQiniu::inst()->getUploadToken($key, ['fsizeLimit' => 100 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
            'url' => \view::upload($key)
        ];
        $this->apiSuccess($data);

    }

    public function _GET_addSeries()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $seriesSn = $this->apiGET('series_sn');
        $ret = servLesson::sole($this->platform)->addSeries($lessonSn, $seriesSn);
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);

    }

    public function _POST_inviteCreate()
    {

        $seriesSn = $this->apiPOST('series_sn');
        $token = uniqid();
        data::redis()->setex("SERIES_INVITE_$token", SECONDS_DAY, $seriesSn);
        $domain = config::load('boot', 'public', 'domain', '', 'Teacher');
        $url = Http::makeURL($_SERVER['REQUEST_SCHEME'] . '://' . $domain . '/series-inviteCreate', [
            'series_sn' => $seriesSn,
            'token' => $token
        ]);
        $this->apiSuccess($url);
    }

    public function _GET_inviteCreate()
    {
        $seriesSn = $this->apiGET('series_sn');
        $token = $this->apiGET('token');
        if ($token && data::redis()->get("SERIES_INVITE_$token") == $seriesSn) {
//            data::redis()->del("SERIES_INVITE_$token");
            $domain = config::load('boot', 'public', 'domain', '', 'Teacher');
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $domain . '#course/create?type=invite&token=' . $token . '&series_sn=' . $seriesSn;
            $this->httpLocation($url);
        } else {
            echo "链接已失效";
        }
    }


}