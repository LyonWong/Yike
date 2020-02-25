<?php

namespace Student;

use _\servRelation;
use Core\unitAPI;
use Core\unitDoAction;

class ctrlTeacher extends ctrlSess
{
    use unitAPI;
    use unitDoAction;

    public function _GET_info()
    {
        $tusn = $this->apiGET('tusn');
        $info = servUser::sole($this->platform)->usn2profile($tusn);
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $info['about'] = \_\servTeacher::sole($this->platform)->datum($tuid)['about'];
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $info['isFollow'] = servRelation::sole($this->platform)->isFollow($this->uid,$tuid);

        $this->apiSuccess($info);
    }

    public function _GET_singleLesson()
    {
        $tusn = $this->apiGET('tusn');
        $list = servLesson::sole($this->platform)->teacherList($tusn);
        $this->apiSuccess($list);

    }

    public function _GET_seriesLesson()
    {
        $tusn = $this->apiGET('tusn');
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $list = \_\servLessonSeries::sole($this->platform)->listByUid($tuid,true);
        $this->apiSuccess($list);

    }



}