<?php

namespace Student;

use _\dataLessonAccess;
use _\servShare;
use _\servTicket;

class ctrlWeixin extends ctrlSess
{
    public function _GET_jsConfig()
    {
        $url = $this->apiGET('url');
        $config = new servShare();
        $ret = $config->wxGetConfig(urldecode($url));
        $this->apiSuccess($ret);
    }

    public function _GET_lesson()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $origin = $this->apiGET('origin', '');
        $orderId = $this->apiGET('order_id', '');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $event = \input::get('event')->value();
        if ($event == 'remind') {
            $args = ['mark' => $this->apiGET('mark', '')];
            dataLessonAccess::sole($this->platform)->append($lessonId, $this->uid, dataLessonAccess::EVENT_REMIND, $args);
        }
        header("Location:" . $domain . '/?v=1#/course/detail/brief?lesson_sn=' . $lessonSn . '&origin=' . $origin . '&order_id=' . $orderId);
        exit;
    }

    public function _GET_series()
    {
        $seriesSn = $this->apiGET('series_sn');
        $origin = $this->apiGET('origin', '');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:$domain/?v=1#/course/series/detail/$seriesSn/brief/?origin=$origin");
        exit;
    }

    public function _GET_refund()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $curMode = $this->apiGET('cur_mode', 'apply');
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'price']);
        $teacherName = servUser::sole($this->platform)->uid2info($lessonInfo['tuid'], 'name')['name'];
        $refundInfo = servTicket::sole($this->platform)->sn2refundInfo(servLesson::sole($this->platform)->id2sn($lessonInfo['id']), $this->uid);
        $url = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student')
            . '/?v=1#/course/reason?lesson_sn=' . $lessonSn
            . '&title=' . $lessonInfo['title']
            . '&price=' . $lessonInfo['price']
            . '&teacher=' . $teacherName
            . '&cur_mode=' . $curMode
            . '&mode=appeal'
            . '&event=' . json_encode($refundInfo);
        header("Location:$url");
        exit;
    }

    public function _GET_refundPage()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $mode = $this->apiGET('mode', 'apply');
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'price']);
        $teacherName = servUser::sole($this->platform)->uid2info($lessonInfo['tuid'], 'name')['name'];
        $url = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student')
            . '/?v=1#/course/refund?lesson_sn=' . $lessonSn
            . '&title=' . $lessonInfo['title']
            . '&price=' . $lessonInfo['price']
            . '&teacher=' . $teacherName
            . '&mode=' . $mode;
        header("Location:$url");
        exit;
    }

    public function _DO_userInfo()
    {
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/user');
        exit;
    }

    public function _DO_userMoney()
    {
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/user/money');
        exit;
    }

    public function _DO_enrolled()
    {
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/user/enrolled');
        exit;
    }

    public function _DO_teacherCourse()
    {
        $tusn = $this->apiGET('tusn');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/course/series/teacher/' . $tusn . '/single');
        exit;
    }

    public function _DO_courseMessageDetail()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $cursor = $this->apiGET('cursor');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/course/message/' . $lessonSn . '/detail/' . $cursor);
        exit;
    }

    public function _DO_courseMessage()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/course/message/' . $lessonSn . '/discuss/');
        exit;
    }

    public function _DO_courseTask()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/course/message/' . $lessonSn . '/task?lesson_sn=' . $lessonSn);
        exit;
    }

    public function _DO_evaluate()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?#/course/evaluate/' . $lessonSn);
        exit;
    }


}