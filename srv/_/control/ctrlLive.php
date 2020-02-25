<?php


namespace _;


use Core\library\Http;
use Core\unitHttp;
use function Qiniu\base64_urlSafeEncode;

class ctrlLive extends ctrlSess
{
    use unitHttp;

    const ERR_NO_LESSON_ACCESS = ['1', 'no access to lesson'];
    const ERR_ILLEGAL_QUOTE_TYPE = ['1', 'illegal quote type `%s`'];
    const ERR_NO_SPEAK_PERMISSION = ['2', 'no speak permission'];
    const ERR_FAILED_QUOTE_MESSAGE = ['3', 'failed to quote message'];


    public function _DO_()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $srvLesson = servLesson::sole($this->platform);
        if(!servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn, $message)) {
//            $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
//            header("Location:" . $domain . '/?#/course/detail/brief?lesson_sn=' . $lessonSn);
            header("Location: /lesson/detail/$lessonSn");
        } else {
            if ($message === 'audition') {
                $srvLesson->audition($lessonSn, $this->uid);
            } else {
                $srvLesson->access($lessonSn, $this->uid);
            }
        }
        if ($event = $this->apiGET('event', '')) {
            $args = ['mark' => $this->apiGET('mark', '')];
            $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
            dataLessonAccess::sole($this->platform)->append($lessonId, $this->uid, dataLessonAccess::EVENT_REMIND, $args);
        }
        \view::tpl('live');
        /*
        if ($this->client == servAdaptor::CLIENT_WXA) {
            $lessonSn = \input::get('lesson_sn')->value();
            \view::tpl('wxa', [
                'method' => 'navigateTo',
                'wxaUrl' => '/page/lesson/play?lesson_sn='.$lessonSn
            ]);
        } else {
            \view::tpl('live');
        }
        */
    }

    public function _GET_content()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $servLesson = servLesson::sole($this->platform);
        if ($servLesson->checkSpeak($lessonSn, $this->usn) == false) {
            $this->apiFailure(self::ERR_NO_SPEAK_PERMISSION, [], [
                'lesson_sn' => $lessonSn,
                'usn' => $this->usn,
            ]);
        }
        $profile = $servLesson->profile($lessonSn);
        $data = $servLesson->sliceRecord(dataLessonRecord::FORM_TIM, $lessonSn, '-', data::TOWARD_NEXT, 0);
        \view::tpl('/live-content')->with('profile', $profile)->with('data', $data);
    }

    public function _GET_discuss()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $fTIM = config::load('boot', 'path', 'log') . '/debug-tim.log';
        $fp = fopen($fTIM, 'r');
        $servLesson = servLesson::sole($this->platform);
        $profile = $servLesson->profile($lessonSn);
        $data = [];
        while ($row = fgets($fp)) {
            if (preg_match("#Group\.CallbackAfterSendMsg.*$lessonSn-D#", $row)) {
                $msg = json_decode($row, true);
                $data[] = [
                    'user' => servUser::sole($this->platform)->usn2profile($msg['From_Account']),
                    'text' => $msg['MsgBody'][0]['MsgContent']['Data'],
                    'time' => date('Y-m-d H:i:s', $msg['MsgTime'])
                ];
            }
        }
        \view::tpl('/live-discuss')->with('profile', $profile)->with('data', $data);
    }

    public function _GET_tim($item)
    {
        switch ($item) {
            case 'user_sig':
                $data = servTIM::sole($this->platform, $this->usn)->tim()->get_user_sig();
                /*
                $domain = config::load('boot', 'public', 'domain');
                $res = Http::inst()->post("$_SERVER[REQUEST_SCHEME]://$domain/_/tim-gen_user_sig", [
                    'platform' => $this->platform,
                    'identifier' => $this->usn
                ]);
                $decode = json_decode($res, true);
                if ($decode && $decode['error'] == 0) {
                    $data = $decode['data'];
                } else {
                    $data = null;
                    $this->apiFailure(self::ERR_UNDEFINED, [$decode ? $decode['message']: null]);
                }
                */
                break;
            default:
                $data = null;
        }
        $this->apiSuccess($data);
    }

    public function _GET_record($type)
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $servLesson = servLesson::sole($this->platform);
        if ($servLesson->checkAccess($lessonSn, $this->usn) == false) {
            $this->apiFailure(self::ERR_NO_LESSON_ACCESS);
        }
        switch ($type) {
            case 'tim':
                $data = $servLesson->getRecord($lessonSn, dataLessonRecord::FORM_TIM);
                break;
            default:
                $data = null;
        }
        $this->apiSuccess($data);
    }

    public function _GET_slice($type)
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $cursor = $this->apiGET('cursor', '-');
        $toward = $this->apiGET('toward', data::TOWARD_NEXT);
        $limit = $this->apiGET('limit', 10);
        $srvCache = servCache::sole($this->platform);
        $caKey = servCache::TAG_LESSON_AUTH."$lessonSn|$this->usn";
        $caRes = $srvCache->get($caKey);
        $servLesson = servLesson::sole($this->platform);
        if ($caRes === false) {
            $caRes = $servLesson->checkAccess($lessonSn, $this->usn) ? 'yes' : 'no';
            $srvCache->set($caKey, $caRes, SECONDS_MINUTE);
        }
        if ($caRes != 'yes') {
            $this->apiFailure(self::ERR_NO_LESSON_ACCESS);
        }
        $ckey = servCache::TAG_LESSON_SLICE."$type|$lessonSn|$cursor|$toward|$limit";
        if (($data = $srvCache->getJson($ckey)) !== null) {
            $this->apiSuccess($data);
        }
        switch ($type) {
            case 'tim':
                $data = $servLesson->sliceRecord(dataLessonRecord::FORM_TIM, $lessonSn, $cursor, $toward, $limit);
                break;
            default:
                $data = [];
        }
        $srvCache->setJson($ckey, $data, (count($data)==$limit) ? SECONDS_MINUTE : 5);
        $this->apiSuccess($data);
    }

    public function _POST_delete()
    {
        $cursor = $this->apiPOST('cursor');
        $res = servLesson::sole($this->platform)->deleteRecordById($cursor, $this->uid);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['Failed to delete']);
        }
    }

    public function _GET_offline()
    {
        $url = \input::get('url', '/')->value();
        \view::tpl('offline')->with('url', $url, false);
    }

    public function _GET_audio_draft()
    {
//        $uniqid = uniqid();
//        $qiniu = servQiniu::inst();
//        $draft = "draft/audio/$uniqid";
//        $target = "lesson/record/$uniqid.mp4";
//        $persistentOps = 'avthumb/mp4|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$target");
//        $preview = config::load('boot', 'public', 'upload') . "/$target";
//        $token = $qiniu->getUploadToken("draft/audio/$uniqid", [
//            'deleteAfterDays' => 1,
//            'persistentOps' => $persistentOps,
//            'persistentPipeline' => config::load('qiniu', 'dora', 'mps'),
//        ]);
//        $data = [
//            'upload' => config::load('qiniu', 'os', 'Upload'),
//            'token' => $token,
//            'key' => $draft,
//            'preview' => $preview,
//        ];
        $uniqid = uniqid();
        $key = "lesson/record/$uniqid";
        $qiniu = servQiniu::inst();
        $token = $qiniu->getUploadToken($key, ['fsizeLimit' => 100 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
            'preview' => config::load('boot', 'public', 'upload') . "/$key",
        ];
        $this->apiSuccess($data);
    }

    public function _GET_audio_check()
    {
        $pid = $this->apiGET('pid');
        $res = Http::inst()->get('http://api.qiniu.com/status/get/prefop?id=' . $pid);
        $res = json_decode($res);
        if ($res->code == 0) {
            $this->apiSuccess();
        } else {
            $this->apiResponse($res->code, $res->desc, $res);
        }
    }

    public function _POST_quote($type)
    {
        $usn = $this->apiPOST('usn');
        $lessonSn = $this->apiPOST('lesson_sn');
        $room = servLesson::sole($this->platform)->sn2room($lessonSn);
        if (servLesson::sole($this->platform)->checkSpeak($lessonSn, $this->usn) == false) {
            $this->apiFailure(self::ERR_NO_SPEAK_PERMISSION, [], [
                'lesson_sn' => $lessonSn,
                'usn' => $this->usn,
            ]);
        }
        $servTIM = servTIM::sole($this->platform);
        switch ($type) {
            case 'text':
                $text = $this->apiPOST('text');
//                $user = servUser::sole($this->platform)->usn2profile($usn);
//                $time = date('H:i');
//                $servTIM->systemMessage($room['teach'], servTIM::SYS_MSG_HINT, "讲师于 $time 引用了[{$user['name']}]的发言");
                $res = $servTIM->quoteMessage($room['teach'], $usn, $text);
                if ($res['ErrorCode'] == 0) {
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_FAILED_QUOTE_MESSAGE, [], $res);
                }
                break;
            default:
                $this->apiFailure(self::ERR_ILLEGAL_QUOTE_TYPE, [$type]);
                break;
        }
    }

    public function _POST_forbid_speak()
    {
        $usn = $this->apiPOST('usn');
        $lessonSn = $this->apiPOST('lesson_sn');
        $room = servLesson::sole($this->platform)->sn2room($lessonSn);
        $srvTIM = servTIM::sole($this->platform);
        $res = [];
        $res['forbid'] = $srvTIM->forbidSendMsg($room['discuss'], $usn, SECONDS_DAY * 100);
        $res['delete'] = $srvTIM->api('group_open_http_svc', 'delete_group_msg_by_sender', [
            'GroupId' => $room['discuss'],
            'Sender_Account' => $usn,
        ]);
        $res['delete'] = json_decode($res['delete'], true);

        if ($res['forbid']['ActionStatus'] == 'FAIL') {
            $this->apiFailure(self::ERR_UNDEFINED, [$res['forbid']['ErrorInfo']], $res);
        } elseif ($res['delete']['ActionStatus'] == 'FAIL') {
            $this->apiFailure(self::ERR_UNDEFINED, [$res['delete']['ErrorInfo']], $res);
        } else {
            $this->apiSuccess($res);
        }
    }

    public function _DO_prepare($opt)
    {
        $srvPrepare = servLessonPrepare::sole($this->platform);
        $lessonSn = $this->apiRequest('lesson_sn');
        if (servLesson::sole($this->platform)->checkSpeak($lessonSn, $this->usn) == false) {
            $this->apiFailure(self::ERR_NO_SPEAK_PERMISSION);
        }
        switch ($opt) {
            case 'slice':
                $cursor = $this->apiGET('cursor', 0);
                $toward = $this->apiGET('toward', data::TOWARD_NEXT);
                $limit = $this->apiGET('limit', 10);
                $data = $srvPrepare->slice($lessonSn, $cursor, $toward, $limit);
                $this->apiSuccess($data);
                break;
            case 'send':
                $cursor = $this->apiPOST('cursor');
                if ($srvPrepare->send($this->usn, $lessonSn, $cursor)) {
                    $this->apiSuccess();
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ['Failed to send message']);
                }
                break;
        }
    }


    public function _POST_invite()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        if (servLesson::sole($this->platform)->checkSpeak($lessonSn, $this->usn) == false) {
            $this->apiFailure(self::ERR_NO_SPEAK_PERMISSION);
        }
        $token = uniqid();
        data::redis()->setex("GUEST_$token", SECONDS_HOUR, $lessonSn);
        $domain = config::load('boot', 'public', 'domain');
        $url = Http::makeURL($_SERVER['REQUEST_SCHEME'] . '://' . $domain . '/live-invite', [
            'lesson_sn' => $lessonSn,
            'token' => $token
        ]);
        $this->apiSuccess($url);
    }

    public function _GET_invite()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $token = \input::get('token')->value();
        $srvLesson = servLesson::sole($this->platform);
        if ($token && data::redis()->get("GUEST_$token") == $lessonSn) {
            $srvLesson->inviteGuest($lessonSn, $this->usn);
            data::redis()->del("GUEST_$token");
        }
        if ($srvLesson->checkSpeak($lessonSn, $this->usn)) {
            $domain = config::load('boot', 'public', 'domain');
            $room = $srvLesson->sn2room($lessonSn);
            servTIM::sole($this->platform)->tim()->group_forbid_send_msg($room['teach'], $this->usn, 0);

            $url = Http::makeURL($_SERVER['REQUEST_SCHEME'] . '://' . $domain . '/live', [
                'isOwner' => 'yes',
                'teacherEnter' => 'yes',
                'lesson_sn' => $lessonSn,
                'teach' => $room['teach'],
                'discuss' => $room['discuss']
            ]);
            $this->httpLocation($url);
        } else {
//            \viewException::halt("No access");
            echo "链接已失效";
        }
    }


    public function _GET_conf()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $srvLesson = servLesson::sole($this->platform);
        $conf = $srvLesson->liveConf($lessonSn,$this->uid);
        $this->apiSuccess($conf);

    }



}