<?php


namespace Admin\teacher;


use Admin\dataTeacher;
use Admin\servTeacher;
use Admin\servUser;

class ctrlCertificate extends ctrl_
{
    protected $scopeKey = 'teacher-certificate';

    public function _DO_()
    {
        $list = servTeacher::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'teacher/list',
            'list' => $list,
            'status_map' => servTeacher::STATUS_MAP,
        ]);
    }

    public function _DO_detail()
    {
        $tuid = \input::get('tuid')->value();
        $datum = servTeacher::sole($this->platform)->datum($tuid);
        $profile = servUser::sole($this->platform)->uid2profile($tuid);
        $info = servUser::sole($this->platform)->uid2info($tuid, 'email,telephone');
        \view::tpl('page', [
            'page'  => 'teacher/certificate',
            'tuid' => $tuid,
            'profile' => $profile,
            'datum' => $datum,
            'info' => $info,
        ]);
    }

    public function _POST_accept()
    {
        $tuid = \input::post('tuid')->value();
        servTeacher::sole($this->platform)->status($tuid, dataTeacher::STATUS_ACCEPTED);
    }

    public function _POST_reject()
    {
        $tuid = \input::post('tuid')->value();
        $remark = \input::post('remark')->value();
        servTeacher::sole($this->platform)->status($tuid, dataTeacher::STATUS_REJECTED);
    }

}