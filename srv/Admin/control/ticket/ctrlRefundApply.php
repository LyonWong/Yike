<?php


namespace Admin\ticket;


use _\dataTicket;
use _\servRefund;
use _\servTicket;
use Admin\dataOrder;
use Admin\dataUser;
use Admin\servLesson;
use Admin\servUser;
use Admin\unitQueryTicket;

class ctrlRefundApply extends ctrl_
{
    protected $scopeKey = 'ticket-refundApply';

//    public function _DO_()
//    {
//        $status = \input::get('status')->value();
//        $list = servTicket::sole($this->platform)->refund($status);
////        $this->apiSuccess($list);
//        $query = [
//            'status' => $status
//        ];
//        \view::tpl('page', [
//            'page' => 'ticket/refund',
//            'query' => $query,
//        ])->with('list', $list);
//    }

    public function _DO_()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'ticket/refund-apply',
            'query' => $query,
            'status_map' => servTicket::STATUS_MAP
        ]);
    }

    public function _DO_apply()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'ticket/refund-apply',
            'query' => $query,
            'status_map' => servTicket::STATUS_MAP
        ]);
    }

    public function _GET_data()
    {
        $query = unitQueryTicket::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servTicket::sole($this->platform)->page(dataTicket::TYPE_REFUND_APPLY,$query, round(($start/$length)+1), $length);
        foreach ($res['pages'] as &$item) {
            $item['content'] = json_decode($item['content'],true);
            $item['lesson'] = servLesson::sole($this->platform)->profile($item['content']['lesson_sn']);
            $item['status'] = servTicket::STATUS_MAP[$item['i_status']];
            $item['refundInfo'] = servTicket::sole($this->platform)->sn2refundInfo($item['content']['lesson_sn'], $item['uid']);
            $item['order'] = dataOrder::sole($this->platform)->getInfo($item['uid'], servLesson::sole($this->platform)->sn2id($item['content']['lesson_sn']));
            $item['order']['order_amount'] /= 100;
            $item['order']['paid_amount'] /= 100;
            $name = dataUser::sole($this->platform)->fetchOne(['id'=>$item['uid']],'name','name');
            $item = [
                $item['id'],
                '<a href="/user/detail?uid='.$item['uid'].'">'.$name.'</a>',
                '<a href="/lesson/detail?lesson_sn='.$item['lesson']['sn'].'">'.$item['lesson']['title'].'</a>',
                $item['refundInfo']['apply']['reason'],
                '<a href="/pay/detail?order_sn='.$item['order']['sn'].'">'.'￥'.$item['order']['paid_amount'].'</a>',
                $item['refundInfo']['apply']['tms_create'],
                '<a href="/teacher/detail?tusn='.$item['lesson']['teacher']['sn'].'">'.$item['lesson']['teacher']['name'].'</a>',
                $item['refundInfo']['apply']['remark'],
                $item['refundInfo']['apply']['tms_update'],
                $item['refundInfo']['apply']['status'],
                '<a id="access-'.$item['id'].'" onclick="operate(0,'. $item['id'].')">同意</a>'.'<a id="reject-'.$item['id'].'" onclick="operate(-1,'. $item['id'].')">拒绝</a>',

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

    public function _POST_operate()
    {
        $operate = $this->apiPOST('operate', -1);
        $ticketId = $this->apiPOST('ticket_id');
        $remark = $this->apiPOST('remark', '');
        $ret = servRefund::sole($this->platform)->dealApply($this->uid, $remark, $operate, $ticketId,true);
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

    public function _GET_detail()
    {
        $ticketId = $this->apiGET('ticket_id');
        $ret = servTicket::sole($this->platform)->refundDetail($ticketId);
    }

}