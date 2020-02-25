<?php


namespace Admin\feedback;


use Admin\servFeedback;

class ctrlList extends ctrl_
{
    public function _DO_()
    {
        $list = servFeedback::sole($this->platform)->getList();
        \view::tpl('page', [
            'page' => 'feedback/list',
            'status_map' => servFeedback::STATUS_MAP,
        ])->with('list', $list);
    }

}