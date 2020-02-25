<?php


namespace Admin\operation;


use Admin\servSettings;

class ctrlSettings extends ctrl_
{
    public function _DO_()
    {
        $this->_DO_list();
    }

    public function _DO_list()
    {
        $list = servSettings::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'operation/settings-list',
            'list' => $list,
            'dict' => servSettings::TYPE_DICT,
            'types' => servSettings::TYPE_MAP
        ]);
    }

    public function _GET_edit($id=null)
    {
        $id = $id ?: \input::get('id')->toInt();
        $detail = servSettings::sole($this->platform)->detail($id);
        \view::tpl('page', [
            'page' => 'operation/settings-edit',
            'detail' => $detail,
            'dict' => servSettings::TYPE_DICT,
            'types' => servSettings::TYPE_MAP
        ]);
    }

    public function _POST_edit($id=null)
    {
        $id = \input::get('id', $id)->toInt();
        $type = \input::post('type')->value();
        $item = \input::post('item')->value();
        $remark = \input::post('remark')->value();
        $datum = \input::post('datum')->value();
        $srv = servSettings::sole($this->platform);
        if ($id) {
            $srv->modify($id, $type, $item, $remark, $datum);
        } else {
            $id = $srv->create($type, $item, $remark, $datum);
        }
        $this->_GET_edit($id);
    }

}