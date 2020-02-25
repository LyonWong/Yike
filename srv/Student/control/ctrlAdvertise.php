<?php


namespace Student;


use Core\unitDoAction;

class ctrlAdvertise extends ctrl_
{
    use unitDoAction;

    public function _DO_jumbotron($key)
    {
        $origin = \input::get('origin', '')->value();
        $url = "/link-enroll?tsn=$key&origin=$origin";
        \view::tpl('advertise/jumbotron', [
            'key' => $key,
            'url' => $url,
        ]);
    }

}