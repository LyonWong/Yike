<?php


namespace Admin\lesson;


use _\dataTicket;
use _\servMpMsg;
use _\servTrigger;
use Admin\dataLesson;
use Admin\dataUser;
use Admin\servLesson;
use Admin\servTicket;
use Admin\unitQueryTicket;

class ctrlReview extends ctrl_
{
    protected $scopeKey = 'lesson-review';


    public function _DO_()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'lesson/review-list',
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
        $res = servTicket::sole($this->platform)->page(dataTicket::TYPE_CREATE_LESSON, $query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $item['status'] = servTicket::STATUS_MAP[$item['i_status']];
            $content = json_decode($item['content'], true);
            $item['lesson'] = servLesson::sole($this->platform)->profile($content['lesson_sn']);
            $lessonTitle = dataLesson::sole($this->platform)->fetchOne(['sn' => $content['lesson_sn']], 'title', 'title');
            $name = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], 'name', 'name');
            $item = [
                $item['id'],
                $content['lesson_sn'],
                '<a href="/teacher/detail?tuid=' . $item['uid'] . '">' . $name . '</a>',
                '<a href="/lesson/detail?lesson_sn=' . $content['lesson_sn'] . '">' . $lessonTitle . '</a>',
                $item['tms_create'],
                $item['remark'],
                $item['status'],
                '<a href="/lesson/review-detail?lesson_sn=' . $content['lesson_sn'] . '&ticket_id=' . $item['id'] . '">' . '审核' . '</a>',

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

    public function _DO_detail()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $ticketId = \input::get('ticket_id')->value();
        $data = servLesson::sole($this->platform)->detail($lessonSn);
        \view::tpl('page', [
            'page' => 'lesson/review-detail',
            'data' => $data,
            'ticket_id' => $ticketId,
            'step_map' => servLesson::STEP_MAP,
        ]);
    }

    public function _POST_pass()
    {
        $lessonSn = \input::post('lesson_sn')->value();
        $ticketId = \input::post('ticket_id')->value();
        //审核通过，课程开放
        $dtmStart = dataLesson::sole($this->platform)->fetchDtmStart($lessonSn);
        /*
        if (strtotime("$dtmStart -1 hour") < time()) {
            $this->apiFailure(self::ERR_UNDEFINED, ["开课至少提前一小时"]);
        }
        */
        servLesson::sole($this->platform)->step($lessonSn, dataLesson::STEP_OPENED);
        servTrigger::sole($this->platform)->onLessonStartSet($lessonSn, $dtmStart);
        servMpMsg::sole($this->platform)->sendReview($lessonSn, true);
        servTicket::sole($this->platform)->updateStatus($ticketId, $this->uid, '', dataTicket::STATUS_AGREE);
        $this->apiSuccess();
    }

    public function _POST_deny()
    {
        $lessonSn = \input::post('lesson_sn')->value();
        $ticketId = \input::post('ticket_id')->value();

        $reason = \input::post('reason')->value();
        servLesson::sole($this->platform)->step($lessonSn, dataLesson::STEP_DENIED);

        servMpMsg::sole($this->platform)->sendReview($lessonSn, false, $reason);
        servTicket::sole($this->platform)->reject($ticketId, $this->uid, $reason);
        $this->apiSuccess();

    }

}