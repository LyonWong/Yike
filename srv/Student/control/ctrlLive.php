<?php


namespace Student;


class ctrlLive extends ctrlSess
{

    public function _DO_()
    {
        $isOwner = $this->apiGET('isOwner');
        $lessonSn = $this->apiGET('lesson_sn');
        $teach = $this->apiGET('teach');
        $discuss = $this->apiGET('discuss');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', '_');
        header("Location:$domain/live?isOwner=$isOwner&lesson_sn=$lessonSn&teach=$teach&teacherEnter=yes&discuss=$discuss#/");
        exit;

    }
}