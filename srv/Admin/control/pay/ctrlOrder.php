<?php

namespace Admin\pay;


use _\data;
use _\servOrigin;
use Admin\dataLesson;
use Admin\dataUser;
use Admin\servLesson;
use Admin\servOrder;
use Admin\unitQueryOrder;
use Admin\wdgtLang;

class ctrlOrder extends ctrl_
{

    protected $scopeKey = 'pay-order';

    public function _DO_()
    {
        $query = unitQueryOrder::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'pay/order',
            'query' => $query,
            'status_map' => servOrder::STATUS_MAP,
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryOrder::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servOrder::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $item['lesson_id']], ['title', 'sn']);
            $userName = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], ['name'], 'name');
            $tier = servOrigin::sole($this->platform)->tier($item['origin_id']);
            unset($tier[0]);
            $originName = implode('-', array_column($tier, 'name'));
            $item['order_amount'] /= 100;
            $item['paid_amount'] /= 100;
            $item['payoff_amount'] /= 100;
            if (substr($item['pay_sn'], 0, 2) == data::SN_UNION_ORDER) {
                $item['pay_sn'] = '<a target="_blank" href="/pay/unionOrder?unionOrderSn=' . $item['pay_sn'] . '">' . $item['pay_sn'] . '</a>';
            }
            $item = [
                $item['sn'],
                servOrder::TYPE_MAP[$item['i_type']],
                '<a href="/lesson/detail?lesson_sn=' . $lesson['sn'] . '">' . $lesson['title'] . '</a>',
                '<a href="/user/detail?uid=' . $item['uid'] . '">' . $userName . '</a>',
                $item['order_amount'],
                $originName,
                servOrder::PAY_WAY_MAP[$item['i_pay_way']],
                $item['pay_sn'],
                $item['paid_amount'],
                wdgtLang::dict(servOrder::STATUS_MAP[$item['i_status']]),
                $item['payoff_amount'],
                $item['tms_create'],
                '<a href="/pay/payoff?orderId='.$item['id'].'">查看分成</a>'
            ];
        }
        $data = [
            'draw' => $draw + 1,
            'recordsTotal' => $res['total'],
            'recordsFiltered' => $res['total'],
            'data' => $res['pages']
        ];
        echo json_encode($data);
    }

    public function _GET_export()
    {
        $query = unitQueryOrder::init($_GET);
        $res = servOrder::sole($this->platform)->export($query);
        $header = ['订单SN','订单类型','课程','用户','订单金额','订单来源','支付方式','支付单号','支付金额','支付状态','讲师分成','创建时间'];
        foreach ($res as &$item) {
            $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $item['lesson_id']], ['title', 'sn']);
            $userName = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], ['name'], 'name');
            $tier = servOrigin::sole($this->platform)->tier($item['origin_id']);
            unset($tier[0]);
            $originName = implode('-', array_column($tier, 'name'));
            $item['order_amount'] /= 100;
            $item['paid_amount'] /= 100;
            $item['payoff_amount'] /= 100;
            $item = [
                $item['sn'],
                servOrder::TYPE_MAP[$item['i_type']],
                $lesson['title'],
                $userName,
                $item['order_amount'],
                $originName,
                servOrder::PAY_WAY_MAP[$item['i_pay_way']],
                $item['pay_sn'],
                $item['paid_amount'],
                wdgtLang::dict(servOrder::STATUS_MAP[$item['i_status']]),
                $item['payoff_amount'],
                $item['tms_create'],
            ];
        }
        \output::csv(basename($this->_WAY_).'-'.date('Ymd'), array_merge([$header], $res));
    }
}