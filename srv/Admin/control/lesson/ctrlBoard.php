<?php


namespace Admin\lesson;



use Admin\servLessonBoard;
use Admin\unitQueryBoard;

class ctrlBoard extends ctrl_
{
    protected $scopeKey = 'lesson-board';
    public function _DO_()
    {
        $query = unitQueryBoard::init($_GET);
        $query->dateStart = \input::get('dateStart', '-15 days')->toDate();
        $query->dateEnd = \input::get('dateEnd', 'today')->toDate();
        $list = servLessonBoard::sole($this->platform)->queryList($query);
//        $this->apiSuccess($list);
        \view::tpl('page', [
            'page' => 'lesson/board',
            'query' => $query,
            'type_map' => servLessonBoard::TYPE_MAP
        ])->with('list', $list);
    }

    public function _POST_delete()
    {
        $boardId = $this->apiPOST('boardId');
        $reason = $this->apiPOST('reason','');
        $ret = servLessonBoard::sole($this->platform)->delete($boardId,$this->uid,$reason);
        if($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

}