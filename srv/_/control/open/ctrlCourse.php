<?php


namespace _\open;


use _\dataLessonAccess;
use _\dataOrder;
use _\dataUserAuth;
use _\servLesson;
use _\servLessonHub;
use _\servLessonSeries;
use _\servUser;
use _\sign\serv;

class ctrlCourse extends ctrl_
{
    const ERR_MISSING_USER_ID = ['1', 'Missing user identification'];
    const ERR_INVALID_USER_ID = ['2', 'Invalid user identification'];
    const ERR_INCORRECT_COURSE_SN = ['3', 'Incorrect course sn `%s`'];
    const ERR_NO_ACCESS_TO_LESSON = ['4', 'You have no access to lesson %s'];

    public function _POST_check()
    {
        $sn = $this->apiPOST('sn');
        $mode = $this->apiPOST('mode', 'access');

        // 检查课程是否存在
        $course = servLessonHub::sole($this->platform)->target($sn);
        if (!$course) {
            $this->apiFailure(self::ERR_INCORRECT_COURSE_SN, [$sn]);
        }
        // 检查是否拥有课程讲师权限
        if ($this->usn != $course['teacher']['sn']) {
            $this->apiFailure(self::ERR_NO_ACCESS_TO_LESSON, [$sn]);
        }

        // 通过手机号判断
        if ($telephone = $this->apiPOST('telephone', 0)) {
            // 判断是否存在手机对应用户
            $res = serv::sole($this->platform)->check(dataUserAuth::TYPE_TELEPHONE, $telephone);
            if (!$res['code']) { // 无法对应到用户
                $this->apiFailure(self::ERR_INVALID_USER_ID);
            }

            // 检查课程状态，返回拥有权限的比例
            $status = $this->check($res['uid'], $sn, $mode);
            $this->apiSuccess($status);
        }
        $this->apiFailure(self::ERR_MISSING_USER_ID);
    }

    public function check($uid, $lesson, $mode)
    {
        $res = [
            'lesson' => 1,
            'enroll' => [], // 可报名课程
            'access' => [], // 可观看课程
            'refund' => [], // 可退款课程
            'events' => [], // 当前课程状态
        ];
        if ($lesson[0] == 'L') { // 单课
            $recent = servLesson::sole($this->platform)->recent($lesson, $uid, [
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS,
                dataLessonAccess::EVENT_REFUND,
                dataLessonAccess::EVENT_RESET
            ]);
            if ($recent['refund_mode'] == 'freely') {
                $res['refund'][] = $lesson;
            }
            switch ($recent['event']) {
                case '':
                case 'reset':
                    $res['enroll'][] = $lesson;
                    break;
                case 'enroll':
                case 'access':
                    $res['access'][] = $lesson;
                    break;
            }
        }
        if ($lesson[0] == 'S') { // 系列课
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $res = servLessonSeries::sole($this->platform)->checkLessons($lesson, $usn);
        }

        // 计算听课比例
        switch ($mode) {
            case 'access':
                return count($res['access']) / $res['lesson'];
            case 'confirm':
                return (count($res['access']) - count($res['refund'])) / $res['lesson'];
            default:
                if ($res['lesson'] == count($res['access']) && count($res['refund']) == 0) {
                    return 1;
                } else {
                    return 0;
                }
        }
    }


}