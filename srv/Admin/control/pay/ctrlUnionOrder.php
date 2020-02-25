<?php

namespace Admin\pay;


use _\dataLessonSeries;
use _\servOrigin;
use Admin\dataLesson;
use Admin\dataUser;
use Admin\servOrder;
use Admin\servUnionOrder;
use Admin\unitQueryUnionOrder;
use Admin\wdgtLang;

class ctrlUnionOrder extends ctrl_
{

    protected $scopeKey = 'pay-unionOrder';

    public function _DO_()
    {
        $query = unitQueryUnionOrder::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'pay/unionOrder',
            'query' => $query,
            'status_map' => servOrder::STATUS_MAP,
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryUnionOrder::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servUnionOrder::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $userName = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], ['name'], 'name');
            $tier = servOrigin::sole($this->platform)->tier($item['origin_id']);
            unset($tier[0]);
            $originName = implode('-', array_column($tier, 'name'));
            $extra = json_decode($item['extra'], true);
            $seriesName = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $extra['series_sn']], 'name', 'name');
            $item['order_amount'] /= 100;
            $item['paid_amount'] /= 100;
            $item = [
                $item['id'],
                $item['sn'],
                servUnionOrder::sole($this->platform)::MAP_TYPE[$item['i_type']],
                '<a href="/user/detail?uid=' . $item['uid'] . '">' . $userName . '</a>',
                $item['order_amount'],
                $originName,
                wdgtLang::dict(servUnionOrder::STATUS_MAP[$item['i_status']]),
                $extra['cost']['total'] / 100,//全系列单课总价(勾选)
                $extra['cost']['series'] / 100,//全系列打包价
                $extra['cost']['prime'] / 100,//订单单课总价
                $extra['cost']['order'] / 100,//订单加权价格
                $extra['cost']['deduct'] / 100,//优惠抵扣价
                $extra['var']['voucher'] ? $extra['var']['voucher'] / 100 : '', //代金券抵扣
                $extra['var']['cash'] ? $extra['var']['cash'] / 100 : '', //余额抵扣
                ($extra['promote'] && $extra['promote'] != 'false') ? $extra['promote'] : '',
                servUnionOrder::PAY_WAY_MAP[$item['i_pay_way']],
                $item['pay_sn'],
                $item['paid_amount'],
                $extra['series_sn'] ? '<a href="/lesson/series-detail?series_sn=' . $extra['series_sn'] . '">' . $seriesName . '</a>' : '',
                $item['tms_create'],
                json_encode($extra['lesson_ids']),
                $item['order_set'] ?? '',
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
        $query = unitQueryUnionOrder::init($_GET);
        $res = servUnionOrder::sole($this->platform)->export($query);
        $header = ['ID', 'SN', '订单类型', '用户', '订单金额', '订单来源', '支付状态', '全系列单课总价(勾选)', '全系列打包价', '订单单课总价', '订单加权价格', '优惠抵扣价', '代金券抵扣', '余额抵扣', '优惠券', '支付方式', '支付单号', '支付金额', '系列课SN', '创建时间'];
        foreach ($res as &$item) {
            $userName = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], ['name'], 'name');
            $tier = servOrigin::sole($this->platform)->tier($item['origin_id']);
            unset($tier[0]);
            $originName = implode('-', array_column($tier, 'name'));
            $extra = json_decode($item['extra'], true);
            $seriesName = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $extra['series_sn'] ?? ''], 'name', 'name');
            $item['order_amount'] /= 100;
            $item['paid_amount'] /= 100;
            $item = [
                $item['id'],
                $item['sn'],
                servUnionOrder::sole($this->platform)::MAP_TYPE[$item['i_type']],
                $userName,
                $item['order_amount'],
                $originName,
                wdgtLang::dict(servUnionOrder::STATUS_MAP[$item['i_status']]),
                ($extra['cost']['total'] ?? 0) / 100,//全系列单课总价(勾选)
                ($extra['cost']['series'] ?? 0) / 100,//全系列打包价
                ($extra['cost']['prime'] ?? 0) / 100,//订单单课总价
                ($extra['cost']['order'] ?? 0) / 100,//订单加权价格
                ($extra['cost']['deduct'] ?? 0) / 100,//优惠抵扣价
                ($extra['var']['voucher'] ?? 0) ? $extra['var']['voucher'] / 100 : '', //代金券抵扣
                ($extra['var']['cash'] ?? 0) ? ($extra['var']['cash'] ?? 0) / 100 : '', //余额抵扣
                (($extra['promote']??0) && $extra['promote'] != 'false') ? $extra['promote'] : '',
                servUnionOrder::PAY_WAY_MAP[$item['i_pay_way']],
                $item['pay_sn'],
                $item['paid_amount'],
                isset($extra['series_sn']) ? $seriesName : '',
                $item['tms_create'],
            ];
        }
        \output::csv('unionOrder-export', array_merge([$header], $res));
    }

    public function _DO_detail()
    {
        $unionOrderSn = $this->apiGET('union_sn');

    }
}