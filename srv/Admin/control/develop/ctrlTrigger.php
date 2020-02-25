<?php


namespace Admin\develop;


use _\servTrigger;
use Admin\data;

class ctrlTrigger extends ctrl_
{
    protected $scopeKey = 'develop-trigger';

    public function _DO_()
    {
        $list = servTrigger::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'develop/trigger',
        ])->with('list', $list);
    }

    public function _POST_delete()
    {
        $key = $this->apiPOST('key');
        $res = servTrigger::sole($this->platform)->expire($key);
        if ($res) {
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['unknown']);
        }
    }

    public function _POST_ignite()
    {
        $key = $this->apiPOST('key');
        $res = servTrigger::sole($this->platform)->expire($key, 1);
        if ($res) {
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['unknown']);
        }
    }

}