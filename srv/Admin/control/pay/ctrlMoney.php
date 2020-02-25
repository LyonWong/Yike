<?php

namespace Admin\pay;


use Admin\servMoney;
use Admin\servOrder;
use Admin\servUser;
use Admin\unitQueryMoney;
use Admin\unitQueryUser;
use Core\library\Language;

class ctrlMoney extends ctrl_
{
    protected $scopeKey = 'pay-money';


    public function _DO_()
    {
        $query = unitQueryUser::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'pay/money-list',
            'query' => $query,
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryUser::init($_GET);
        $query->dateStart = '2016-01-22';
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servUser::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {

            $countList = servMoney::sole($this->platform)->countList($item['id']);
            $item = [
                $item['id'],
                '<a href="/user/detail?uid=' . $item['id'] . '">' . $item['name'] . '</a>',
                '<a href="/pay/money-detail?uid=' . $item['id'] . '">' . $countList['balance'] . '</a>',
                $countList['income'],
                $countList['expend']];
        }
        $data = [
            'draw' => $draw + 1,
            'recordsTotal' => $res['total'],
            'recordsFiltered' => $res['total'],
            'data' => $res['pages']
        ];
        echo json_encode($data);
    }

    public function _GET_detail()
    {
        $query = unitQueryMoney::init($_GET);
        $uid = $this->apiGET('uid', '');
        $query->dateStart = \input::get('dateStart', '-15 days')->toDate();
        $query->dateEnd = \input::get('dateEnd', 'today')->toDate();
        $query->uid = $uid;
        $list = servMoney::sole($this->platform)->queryDetailList($query);
        \view::tpl('page', [
            'page' => 'pay/money-detail-list',
            'query' => $query,
            'item_map' => servMoney::ITEM_MAP,
        ])->with('list', $list);
    }
}