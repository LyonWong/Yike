<?php


namespace _\api;


use _\servRelation;
use _\servUser;

class ctrlFollow extends ctrlSigned
{
    public function _POST_teacher()
    {
        $tusn = $this->apiPOST('usn');
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $ret = servRelation::sole($this->platform)->follow($this->uid, $tuid);
        $this->apiSuccess($ret);
    }

}