<?php


namespace Admin\lesson;


use _\config;
use _\dataLesson;
use Admin\servLesson;
use Core\unitHttp;

class ctrlDetail extends ctrl_
{
    use unitHttp;

    protected $scopeKey = 'lesson-detail';

    public function _DO_()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $data = servLesson::sole($this->platform)->detail($lessonSn);
//        $this->apiSuccess($data);
        \view::tpl('page', [
            'page' => 'lesson/detail',
            'step_map' => servLesson::STEP_MAP,
        ])->with('data', $data);
    }

    public function _DO_inspect()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $query = servLesson::sole($this->platform)->sn2room($lessonSn);
        $query['lesson_sn'] = $lessonSn;
        $query['isOwner'] = 'yes';
        $domain = config::load('boot', 'public', 'domain');
        $target = "$_SERVER[REQUEST_SCHEME]://$domain/live?" . http_build_query($query);
        $this->httpLocation($target);
    }

    public function _DO_conf()
    {
        $lessonSn = \input::get('lesson_sn')->value();

        $conf = servLesson::sole($this->platform)->conf($lessonSn);
        $activityConf = servLesson::sole($this->platform)->activityConf($lessonSn);
        \view::tpl('page', [
            'page' => 'lesson/conf',
        ])->with('conf', json_encode($conf), false)
            ->with('activity_conf', json_encode($activityConf), false)
            ->with('lesson_sn', $lessonSn);
    }

    public function _POST_conf()
    {
        $conf = $this->apiPOST('conf');
        $lessonSn = $this->apiPOST('lesson_sn');
//        $conf = str_replace("\n", '', $conf);
//        $conf = str_replace(" ", '', $conf);
        if(!$this->is_json($conf)) {
            $this->apiFailure(['1.1','配置不是正确的json格式']);
        }
        $ret = servLesson::sole($this->platform)->updateConf($lessonSn, json_decode($conf, true));
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(['1.2', '更新失败']);
    }

    public function _POST_activityConf()
    {
        $conf = $this->apiPOST('conf');
        $lessonSn = $this->apiPOST('lesson_sn');
        $conf = str_replace("\n", '', $conf);
        $conf = str_replace(" ", '', $conf);
        if(!$this->is_json($conf)) {
            $this->apiFailure(['1.1','配置不是正确的json格式']);
        }
        $ret = servLesson::sole($this->platform)->updateActivityConf($lessonSn, $conf);
        if ($ret) {
            $this->apiSuccess($conf);
        }
        $this->apiFailure(['1.2', '更新失败']);
    }

    public function _POST_edit()
    {
        $lessonSn = $this->apiPOST('sn');
        $params = $this->apiRequest(null);
        $cover = $_FILES['cover']['tmp_name'] ?? null;
        $banner = $_FILES['banner']['tmp_name'] ?? null;
        $ret = servLesson::sole($this->platform)->updateByparams($lessonSn,$params,$cover, $banner);
        if($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(['1.3', '修改失败']);
    }

    public function _POST_block()
    {
        $lessonSn = $this->apiPOST('sn');
        $reason = $this->apiPOST('reason');
        $res = servLesson::sole($this->platform)->step($lessonSn, dataLesson::STEP_CLOSED);
        if ($res) {
            $info = servLesson::sole($this->platform)->sn2info($lessonSn, ['tuid', 'title']);
            $first = "您发布的《$info[title]》由于 $reason , 被管理员下架\r\n";
            $time = date('Y-m-d H:i:s');
            \_\servMpMsg::sole($this->platform)->toUserNotice($info['tuid'], $first, '课程下架通知', $time, "多次违规将取消讲师资格");
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['failed to block']);
        }
    }

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


}