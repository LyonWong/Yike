<?php


namespace _\api;


use _\servLessonSeries;

class ctrlSeries extends ctrl_
{
    public function _DO_profile()
    {
        $sn = $this->apiGET('sn');
        $data = servLessonSeries::sole($this->platform)->sn2profile($sn);
        $this->apiSuccess($data);
    }

    public function _DO_introduce()
    {
        $sn = $this->apiGET('sn');
        $data = servLessonSeries::sole($this->platform)->sn2introduce($sn);
        $this->apiSuccess($data);
    }

    public function _DO_relative()
    {
        $sn = $this->apiGET('sn');
        $data = servLessonSeries::sole($this->platform)->sn2relative($sn);
        $this->apiSuccess($data);
    }

    public function _DO_conf()
    {
        $sn = $this->apiGET('sn');
        $conf = servLessonSeries::sole($this->platform)->conf($sn);
        $this->apiSuccess($conf);
    }

}