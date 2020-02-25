<?php


namespace Admin\ticket;


use _\dataTicket;
use _\servMpMsg;
use _\servTIM;
use Admin\dataUser;
use Admin\servFeedback;
use _\servTicket;
use Admin\unitQueryTicket;

class ctrlFeedback extends ctrl_
{
    protected $scopeKey = 'ticket-feedback';


    public function _DO_()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'ticket/feedback',
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
        $res = servTicket::sole($this->platform)->page(dataTicket::TYPE_FEEDBACK,$query, round(($start/$length)+1), $length);
        foreach ($res['pages'] as &$item) {
            $item['status'] = servTicket::STATUS_MAP[$item['i_status']];
            $content = json_decode($item['content'], true);
            $name = dataUser::sole($this->platform)->fetchOne(['id'=>$item['uid']],'name','name');
            $item = [
                $item['id'],
                '<a href="/user/detail?uid='.$item['uid'].'">'.$name.'</a>',
                $content['text'],
                $content['image'] ? '<img style="cursor:pointer" src="'.\view::upload($content['image']) . '?imageView2/0/w/60'.'" data-toggle="modal" data-target="#myModal" title="点击查看大图" data-imgsrc="'.\view::upload($content['image']).'">' : '',
                $item['remark'],
                $item['tms_create'],
                $item['status'],
                ($item['i_status'] != dataTicket::STATUS_CLOSE) ? '<a id="close-'.$item['id'] .'" onclick="closeFeedBack('.$item['id'].')">关闭</a>' : ''
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

    public function _POST_close()
    {
        $ticketId = $this->apiPOST('ticket_id');
        $remark = $this->apiPOST('remark', '');
        $send = $this->apiPOST('send');
        servTicket::sole($this->platform)->close($ticketId, $this->uid, $remark);
        if($send) {
            servMpMsg::sole($this->platform)->sendFeedback($ticketId);
        }
    }


}