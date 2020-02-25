<?php


namespace Admin\ticket;


use _\dataLessonSeries;
use _\dataTicket;
use _\servTicket;
use Admin\unitQueryTicket;
use Admin\dataLesson;
use Admin\dataUser;
use Admin\servLesson;

class ctrlSeries extends ctrl_
{
    protected $scopeKey = 'ticket-series';
    

    public function _DO_()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'ticket/series',
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
        $res = servTicket::sole($this->platform)->page(dataTicket::TYPE_MODIFY_SERIES,$query, round(($start/$length)+1), $length);
        foreach ($res['pages'] as &$item) {
            $item['status'] = servTicket::STATUS_MAP[$item['i_status']];
            $content = json_decode($item['content'], true);
            $item['lesson'] = servLesson::sole($this->platform)->profile($content['lesson_sn']);
            $seriesName  = dataLessonSeries::sole($this->platform)->fetchOne(['sn'=>$content['series_sn']],'name','name');
            $name = dataUser::sole($this->platform)->fetchOne(['id'=>$item['uid']],'name','name');
            $item = [
                $item['id'],
                '<a href="/lesson/series-detail?series_sn='.$content['series_sn'].'">'.$seriesName.'</a>',
                '<a href="/teacher/detail?tuid='.$item['uid'].'">'.$name.'</a>',
                $item['tms_create'],
                $item['remark'],
                $item['status'],
                '<a href="/ticket/series-detail?ticketId='.$item['id'].'">'.'详情'.'</a>',

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

    public function _GET_detail()
    {
        $ticketId = \input::get('ticketId')->value();
        $ret = servTicket::sole($this->platform)->seriesDetail($ticketId);
//        $this->apiSuccess($ret);

        \view::tpl('page', [
            'page' => 'ticket/series-detail',
            'detail' => $ret,
        ]);
    }

    public function _POST_pass()
    {
        $ticketId = $this->apiPOST('ticketId');
        $remark = $this->apiPOST('remark', '');
        $ret = servTicket::sole($this->platform)->dealSeries($this->uid,$ticketId,dataTicket::STATUS_AGREE,$remark);
        if ($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }
    public function _POST_deny()
    {
        $ticketId = $this->apiPOST('ticketId');
        $remark = $this->apiPOST('remark', '');
        $ret = servTicket::sole($this->platform)->dealSeries($this->uid,$ticketId,dataTicket::STATUS_REJECT,$remark);
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

}