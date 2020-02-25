<?php
namespace Admin\pay;


use Admin\dataOrder;
use Admin\dataRefund;
use Admin\servLesson;
use Admin\servRefund;
use Admin\servUser;
use Admin\unitQueryRefund;
use Admin\wdgtLang;

class ctrlRefund extends ctrl_
{

    protected $scopeKey = 'pay-refund';
    public function _DO_()
    {
        $query = unitQueryRefund::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
//        $list = servRefund::sole($this->platform)->queryList($query);
        \view::tpl('page', [
            'page' => 'pay/refund',
            'status_map' => servRefund::STATUS_MAP,
            'query' => $query,
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryRefund::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servRefund::sole($this->platform)->page($query, round(($start/$length)+1), $length);
        foreach ($res['pages'] as &$item) {

            $item['user'] = servUser::sole($this->platform)->uid2profile($item['uid']);
            $item['lesson_id'] = dataOrder::sole($this->platform)->fetchOne(['id' => $item['order_id']], 'lesson_id', 'lesson_id');
            $item['lesson'] = servLesson::sole($this->platform)->profile($item['lesson_id'], 'id');
            $item['amount'] /= 100;
            $item = [
                $item['sn'],
                $item['order_id'],
                '<a href="/lesson/detail?lesson_sn='.$item['lesson']['sn'].'">'.$item['lesson']['title'].'</a>',
                '<a href="/user/detail?uid='.$item['uid'].'">'.$item['user']['name'].'</a>',
                '￥ '.$item['amount'],
                wdgtLang::dict(servRefund::STATUS_MAP[$item['i_status']]),
                $item['tms_create'],
                $item['tms_update'],
                $item['tms_finish'],
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