<?php
namespace Admin\manage;


use Admin\dataUser;
use Admin\servUser;

class ctrlUser extends ctrl_
{


    public function _DO_()
    {
        $list = servUser::sole($this->platform)->getList(dataUser::ROLE_ADMIN);
        \view::tpl('page', [
            'page' => 'manage/user',
            'role_map' =>servUser::ROLE_MAP,
        ])->with('list', $list);
    }
}