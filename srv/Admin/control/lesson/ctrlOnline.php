<?php


namespace Admin\lesson;


use Admin\servLesson;

class ctrlOnline extends ctrl_
{
    protected $scopeKey = 'lesson-online';
    public function _DO_()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $lesson = servLesson::sole($this->platform)->profile($lessonSn);
        $list = servLesson::sole($this->platform)->onlineList($lessonSn);
//        $this->apiSuccess($lesson);
        \view::tpl('page', [
            'page' => 'lesson/online',
            'lesson' => $lesson,
            'count' => count($list),
        ])->with('list', $list);
    }

    public function _POST_forbidSendMsg()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $usn = $this->apiPOST('usn');

        servLesson::sole($this->platform)->forbidSendMsg($lessonSn,$usn);

    }

    public function _POST_deleteGroupMember()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $usn = $this->apiPOST('usn');

        servLesson::sole($this->platform)->deleteGroupMember($lessonSn,$usn);
    }

}