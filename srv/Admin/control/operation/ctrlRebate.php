<?php


namespace Admin\operation;


use Admin\servLesson;
use Admin\servRebate;
use Admin\servUser;

class ctrlRebate extends ctrl_
{
    public function _DO_()
    {
    }

    public function _GET_single()
    {
        $usn = \input::get('usn')->value();
        if ($usn) {
            $uid = servUser::sole($this->platform)->usn2uid($usn);
            $user = servUser::sole($this->platform)->detail($uid);
        } else {
            $user = null;
        }
        \view::tpl('page', [
            'page' => 'operation/rebate-single'
        ])->with('user', $user);
        $this->debugOnJSC('user', $user);
    }

    public function _GET_lesson()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $percent = \input::get('percent')->value();
        $deadline = \input::get('deadline')->value();
        if ($lessonSn) {
            $lesson = servLesson::sole($this->platform)->detail($lessonSn);
            $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
            $lesson['stats'] = \_\stats\servLesson::sole($this->platform)->getSummary($lessonId);
        } else {
            $lesson = null;
        }
        if ($percent) {
            $preview = servRebate::sole($this->platform)->lesson($lessonSn, $percent, $deadline, '', true);
        } else {
            $preview = null;
        }
        \view::tpl('page', [
            'page' => 'operation/rebate-lesson',
        ])
            ->with('lesson', $lesson)
            ->with('percent', $percent)
            ->with('deadline', $deadline)
            ->with('preview', $preview);

        $this->debugOnJSC('lesson', $preview);
    }

    public function _POST_single()
    {
        $usn = $this->apiPOST('usn');
        $amount = $this->apiPOST('amount');
        $info = $this->apiPOST('info', 'gift');
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $res = servRebate::sole($this->platform)->single($uid, $amount, $info);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }

    public function _POST_lesson()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $percent = $this->apiPOST('percent');
        $deadline = $this->apiPOST('deadline');
        $info = $this->apiPOST('info');
        $data = servRebate::sole($this->platform)->lesson($lessonSn, $percent, $deadline, $info);
        $this->apiSuccess($data);
    }
}