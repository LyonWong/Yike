<?php

namespace Student;

use Core\unitAPI;


class ctrlShare extends ctrlSess
{
    use unitAPI;


    public function _GET_()
    {
        $lessonSn = $this->apiGET('lesson_sn', '');
        $origin = $this->apiGET('origin', '');
        $ad = $this->apiGET('ad', '');
        $v = $this->apiGet('v', '0');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . '/?v=' . $v . '#/course/detail/brief?lesson_sn=' . $lessonSn . '&origin=' . $origin);
        exit;
    }

    public function _GET_series()
    {
        $seriesSn = $this->apiGET('series_sn');
        $origin = $this->apiGET('origin', '');
        $v = $this->apiGET('v', '0');
        $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        header("Location:" . $domain . "/?v=$v#/course/series/detail/". $seriesSn . '/brief/?origin=' . $origin);
        exit;
    }

}