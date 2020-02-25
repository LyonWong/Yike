<?php


namespace Admin\lesson;


use _\dataLessonRecord;
use Admin\dataLesson;
use Admin\servLesson;

use Admin\servUser;

class ctrlRecord extends ctrl_
{
    protected $scopeKey = 'lesson-list';

    public function _DO_()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $type = \input::get("type")->value();
        $tuid = dataLesson::sole($this->platform)->fetchOne(['sn'=>$lessonSn],'tuid','tuid');
        $teacher = servUser::sole($this->platform)->uid2profile($tuid);
        $list = servLesson::sole($this->platform)->listRecord($lessonSn, $type);
        foreach ($list as &$item) {
            if($item['from_uid'] == $tuid) {
                $item['user'] = $teacher;
            } else {
                $item['user'] = servUser::sole($this->platform)->uid2profile($item['from_uid']);
            }
        }
        \view::tpl('page', [
            'page' => 'lesson/record',
            'forms' => servLesson::RECORD_FORM_MAP,
            'lessonSn' => $lessonSn,
        ])->with('list', $list);
    }

    public function _GET_edit()
    {
        $id = \input::get('id')->value();
        $data = servLesson::sole($this->platform)->viewRecord($id);
        $lessonSn = \input::get('lesson_sn')->value();
        $form = \input::get('form')->value();
        $type = \input::get('type', $data['content']['type']??null)->value();
        \view::tpl('page', [
            'page' => 'lesson/record-edit',
            'lessonSn' => $lessonSn,
            'form' => $form,
            'type' => $type,
            'data' => $data
        ]);
    }

    public function _POST_edit()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $form = $this->apiGET('form');
        $content = \input::post('content')->value();
        $id = $this->apiPOST('id', 0);
//        var_dump($content);exit;
        $content = json_encode($content);
        if ($id) { // 修改
            servLesson::sole($this->platform)->modifyRecord($id, $content);
        } else { // 新记录
            $id = servLesson::sole($this->platform)->createRecord($lessonSn, $this->usn, $form, $content);
        }
        $this->httpLocation("./record-edit?form=$form&id=$id&lesson_sn=$lessonSn");
        exit;
        if ($ret) {
            $this->apiSuccess($ret);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }

    public function _POST_delete()
    {
        $recordId = $this->apiPOST('recordId');
        $ret = servLesson::sole($this->platform)->deleteRecord($recordId);
        if($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

    public function _POST_modify()
    {
        $recordId = $this->apiPOST('recordId');
        $content = $this->apiPOST('content');
        $ret = servLesson::sole($this->platform)->modifyRecord($recordId,$content);
        if($ret) {
            $this->apiSuccess($content);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }



}