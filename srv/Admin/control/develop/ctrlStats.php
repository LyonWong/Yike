<?php


namespace Admin\develop;


use Admin\stats\servTimely;

class ctrlStats extends ctrl_
{
    public function _DO_timely()
    {
        $data = servTimely::sole($this->platform)->all();
        \view::tpl('page', [
            'page' => 'develop/stats-timley',
        ])->with('data', $data);
    }

}