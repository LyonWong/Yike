<?php


namespace _\api;


use _\servLessonHub;

class ctrlMy extends ctrlSigned
{
    public function _DO_lesson($opt=null)
    {
        switch ($opt) {
            case 'sub':
                $seriesSn = $this->apiGET('sn');
                $list = servLessonHub::sole($this->platform)->owns($this->uid, $seriesSn);
                break;
            default:
                $list = servLessonHub::sole($this->platform)->own($this->uid);
                break;

        }
        $this->apiSuccess($list);
    }


}