<?php


namespace _\api;


use _\servLessonHub;
use _\servRelation;
use _\servTeacher;
use _\servUser;
use _\unitTeacherDatum;

class ctrlTeacher extends ctrl_
{
    public function _GET_datum()
    {
        $tusn = $this->apiGET('usn');
        $user = $this->getUser();
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $datum = array_merge(
            servUser::sole($this->platform)->usn2profile($tusn),
            servTeacher::sole($this->platform)->datum($tuid),
            ['followed' => $user ? servRelation::sole($this->platform)->isFollow($user['uid'], $tuid) : null]
        );
        $this->apiSuccess($datum);
    }

    // 讲师对外可见的课程
    public function _GET_lesson()
    {
        $tusn = $this->apiGET('usn');
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $list = servLessonHub::sole($this->platform)->overt($tuid);
        $this->apiSuccess($list);
    }

    public function _GET_stats()
    {
        $tusn = $this->apiGET('usn');
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $data = servTeacher::sole($this->platform)->stats($tuid);
        $this->apiSuccess($data);
    }

}