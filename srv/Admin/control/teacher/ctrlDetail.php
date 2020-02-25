<?php


namespace Admin\teacher;


use Admin\servTeacher;
use Admin\servUser;

class ctrlDetail extends ctrl_
{
    protected $scopeKey = 'teacher-list';

    public function _DO_()
    {
        $tusn = $this->apiGET('tusn', '');
        $tuid = $this->apiGET('tuid', servUser::sole($this->platform)->usn2uid($tusn));
        $detail = servTeacher::sole($this->platform)->detail($tuid);
        $profile = servUser::sole($this->platform)->uid2profile($tuid);
//        $this->apiSuccess($detail);
        \view::tpl('page', [
            'page' => 'teacher/detail',
        ])
            ->with('detail', $detail)
            ->with('profile', $profile)
            ->with('tuid', $tuid);
    }


    public function _POST_edit()
    {
        $uid = $this->apiPOST('uid');
        if (isset($_FILES['cover']['tmp_name']) && $_FILES['cover']['tmp_name'] != null) {
            servUser::sole($this->platform)->uploadAvatar($uid, $_FILES['cover']['tmp_name']);
        }
        $name = $this->apiPOST('name');
        if ($name) {
            servUser::sole($this->platform)->updateName($uid, $name);
        }
        $about = $this->apiPOST('about');
        servTeacher::sole($this->platform)->updateAbout($uid, $about);

        $this->apiSuccess([$uid, $name, $_FILES['cover']['tmp_name'], $about]);

    }


}