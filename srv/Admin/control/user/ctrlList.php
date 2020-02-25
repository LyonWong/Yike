<?php


namespace Admin\user;


use _\servOrigin;
use Admin\servUser;
use Admin\unitQueryUser;

class ctrlList extends ctrl_
{
    protected $scopeKey = 'user-list';
    public function _DO_()
    {
        $query = unitQueryUser::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'user/list',
            'role_map' => servUser::ROLE_MAP,
            'subscrible_map' => servUser::SUBSCRIBLE_MAP,
            'query' => $query
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryUser::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servUser::sole($this->platform)->page($query, round(($start/$length)+1), $length);
        foreach ($res['pages'] as &$item) {
            $info = json_decode($item['info'],true);
            $tier = servOrigin::sole($this->platform)->tier($item['origin_id']);
            unset($tier[0]);
            $originName = implode('-', array_column($tier, 'name'));
            $item = [
                $item['id'],
                $item['sn'],
                '<img class="avatar" src="'.\view::upload("user/$item[sn]/avatar").'"/> '.' <a href="/user/detail?usn='.$item['sn'].'">'.$item['name'].'</a>',
                $originName,
                isset($info['subscribe']) ? ($info['subscribe'] == 1 ? servUser::SUBSCRIBE_SCENE_MAP[($info['subscribe_scene']??null)] : '已取关') : '未关注',
                $item['tms_create'],
                $item['tms_update']
            ];
        }
        $data = [
            'draw' => $draw+1,
            'recordsTotal' => $res['total'],
            'recordsFiltered' => $res['total'],
            'data' => $res['pages']
        ];
        echo json_encode($data);
    }

}