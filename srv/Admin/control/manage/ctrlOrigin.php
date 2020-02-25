<?php


namespace Admin\manage;


use _\servOrigin;

class ctrlOrigin extends ctrl_
{
    protected $scopeKey = 'manage-origin';

    public function _DO_()
    {
        $oid = \input::get('oid', 0)->value();
        $servOrigin = servOrigin::sole($this->platform);
        $tier = $servOrigin->tier($oid);
        $list  = $servOrigin->listSubs($oid);
        \view::tpl('page', [
            'page' => 'manage/origin',
        ])
            ->with('tier', $tier)
            ->with('list', $list);
    }

    public function _POST_name($oid)
    {
        $value = \input::post('value')->value();
        servOrigin::sole($this->platform)->name($oid, $value);
    }

    public function _POST_new()
    {
        $key = \input::post('key')->value();
        $name = \input::post('name')->value();
        $srv = servOrigin::sole($this->platform);
        $oid = $srv->checkIn($key, $name);
        $this->httpLocation("./origin?oid=$oid");
    }

}