<?php


namespace Admin\lesson;



use Admin\data;
use Admin\servLessonBoard;
use _\servLessonPrepare;
use Admin\servLessonProcess;

class ctrlPrepare extends ctrl_
{
    protected $scopeKey = 'lesson-list';
    public function _DO_()
    {

        $lessonSn = $this->apiGET('lesson_sn');
        $list = servLessonPrepare::sole($this->platform)->slice($lessonSn, 0, data::TOWARD_NEXT, 0);
//        $this->apiSuccess($list);
        \view::tpl('page', [
            'page' => 'lesson/prepare',
        ])->with('list', $list)
        ->with('lesson_sn',$lessonSn);
    }

    public function _POST_delete()
    {
        $cursor = $this->apiPOST('cursor');
        $lessonSn = $this->apiPOST('lesson_sn');
        $ret = servLessonPrepare::sole($this->platform)->delete($lessonSn,$cursor);
        if($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

    public function _POST_modify()
    {
        $cursor = $this->apiPOST('cursor');
        $lessonSn = $this->apiPOST('lesson_sn');
        $text = $this->apiPOST('text');
        $ret = servLessonPrepare::sole($this->platform)->modify($lessonSn,$cursor, 'text', $text);
        if($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

}