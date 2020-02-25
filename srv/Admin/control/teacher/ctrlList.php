<?php


namespace Admin\teacher;


use _\dataTeacher;
use Admin\servTeacher;
use Admin\servUser;

class ctrlList extends ctrl_
{
    protected $scopeKey = 'teacher-list';

    public function _DO_()
    {
        $list = servTeacher::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'teacher/list',
            'status_map' => servTeacher::STATUS_MAP,
        ])->with('list', $list);
    }

    public function _POST_invite()
    {
        $email = $this->apiPOST('email');
        $res = servTeacher::sole($this->platform)->sendInviteEmail($email);
        $this->apiSuccess($res);
    }


    public function _POST_status()
    {
        $tusn = $this->apiPOST('name');
        $iStatus  = $this->apiPOST('value');
        $ret = servTeacher::sole($this->platform)->status(servUser::sole($this->platform)->usn2uid($tusn),$iStatus);
        $this->apiSuccess($ret);
    }

}