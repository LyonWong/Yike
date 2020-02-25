<?php


namespace Student;


use _\dataRelation;
use _\servRelation;

class ctrlFollow extends ctrlSess
{


    public function _POST_follow()
    {
        $tusn = $this->apiPOST('tusn');
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $ret = servRelation::sole($this->platform)->follow($this->uid, $tuid);
        $this->apiSuccess($ret);
    }

    public function _GET_list()
    {
        $cursor = (int)$this->apiGET('cursor', 0);
        $limit = (int)$this->apiGET('limit', 10);
        $list = servRelation::sole($this->platform)->followList($this->uid, $cursor, $limit);
        $this->apiSuccess($list);
    }


}