<?php


namespace Admin\develop;

use _\weixin\servMp;

class ctrlMp extends ctrl_
{
    protected $scopeKey = 'develop-mp';

    public function _DO_()
    {
        $button = servMp::sole($this->platform)->getMenuData();
        \view::tpl('page', [
            'page' => 'develop/mp'
        ])->with('button', $button,false);
    }


    public function _POST_change()
    {
        $button = $this->apiPOST('button');
        if(!$this->is_json($button)) {
            $this->apiFailure(['1.1','配置不是正确的json格式']);
        }
        $ret = servMp::sole($this->platform)->createMenu($button);
        if($ret['errcode'] !== 0) {
            $this->apiFailure(['1.2',$ret['errcode'].':'.$ret['errmsg']]);
        }
        $this->apiSuccess($ret);

    }

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



}