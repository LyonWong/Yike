<?php


namespace Admin\lesson;


use _\dataLessonSeries;
use _\dataTicket;
use _\servMpMsg;
use Admin\dataLesson;
use Admin\dataUser;
use Admin\servLesson;
use Admin\servLessonSeries;
use Admin\servTicket;
use Admin\unitQuerySeries;
use Admin\unitQueryTicket;

class ctrlSeries extends ctrl_
{
    protected $scopeKey = 'lesson-series';


    public function _GET_list()
    {
        $query = unitQuerySeries::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'lesson/series-list',
            'query' => $query,
        ]);
//
//        $list = servLessonSeries::sole($this->platform)->seriesList();
////                $this->apiSuccess($list);
//        \view::tpl('page', [
//            'page' => 'lesson/series-list',
//            'list' => $list,
//        ]);
    }

    public function _GET_data()
    {
        $query = unitQuerySeries::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servLessonSeries::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $name = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], 'name', 'name');
            $item = [
                $item['id'],
                $item['sn'],
                '<a href="/teacher/detail?tuid=' . $item['uid'] . '">' . $name . '</a>',
                '<a href="/lesson/series-detail?series_sn=' . $item['sn'] . '">' . $item['name'] . '</a>',
                $item['tms_create'],
                '<a href="./series-conf?series_sn='.$item['sn'].'">配置</a>
<a href="/lesson/list?step=*&seriesField=sn&seriesValue='.$item['sn'].'">子课程</a>',

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


    public function _GET_review()
    {
        $query = unitQueryTicket::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'lesson/series-review-list',
            'query' => $query,
            'status_map' => servTicket::STATUS_MAP
        ]);
    }


    public function _GET_reviewData()
    {
        $query = unitQueryTicket::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servTicket::sole($this->platform)->page(dataTicket::TYPE_CREATE_SERIES, $query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $item['status'] = servTicket::STATUS_MAP[$item['i_status']];
            $content = json_decode($item['content'], true);
            $item['lesson'] = servLesson::sole($this->platform)->profile($content['lesson_sn']);
            $seriesName = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $content['series_sn']], 'name', 'name');
            $name = dataUser::sole($this->platform)->fetchOne(['id' => $item['uid']], 'name', 'name');
            $item = [
                $item['id'],
                $content['series_sn'],
                '<a href="/teacher/detail?tuid=' . $item['uid'] . '">' . $name . '</a>',
                '<a href="/lesson/series-detail?series_sn=' . $content['series_sn'] . '">' . $seriesName . '</a>',
                $item['tms_create'],
                $item['remark'],
                $item['status'],
                '<a href="/lesson/series-reviewDetail?series_sn=' . $content['series_sn'] . '&ticket_id=' . $item['id'] . '">' . '审核' . '</a>',

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
        $seriesSn = \input::get('series_sn')->value();
        $data = servLessonSeries::sole($this->platform)->detail($seriesSn);
//        $this->apiSuccess($data);
        \view::tpl('page', [
            'page' => 'lesson/series-detail',
            'data' => $data,
        ]);
    }

    public function _DO_reviewDetail()
    {
        $seriesSn = \input::get('series_sn')->value();
        $ticketId = \input::get('ticket_id')->value();
        $data = servLessonSeries::sole($this->platform)->detail($seriesSn);
//        $this->apiSuccess($data);
        \view::tpl('page', [
            'page' => 'lesson/series-review',
            'ticket_id' => $ticketId,
            'data' => $data,
        ]);
    }

    public function _POST_pass()
    {
        $seriesSn = \input::post('series_sn')->value();
        $ticketId = \input::post('ticket_id')->value();
        $ret = servLessonSeries::sole($this->platform)->status($seriesSn, dataLessonSeries::STATUS_OPENED);
        servTicket::sole($this->platform)->updateStatus($ticketId, $this->uid, '', dataTicket::STATUS_AGREE);
        if ($ret) {
            servMpMsg::sole($this->platform)->sendSeriesReview($seriesSn, true);

        }
        $this->apiSuccess($ret);
    }

    public function _POST_deny()
    {
        $seriesSn = \input::post('series_sn')->value();
        $reason = \input::post('reason')->value();
        $ticketId = \input::post('ticket_id')->value();
        servLesson::sole($this->platform)->step($seriesSn, dataLessonSeries::STATUS_DENIED);
        servMpMsg::sole($this->platform)->sendSeriesReview($seriesSn, false, $reason);
        servTicket::sole($this->platform)->reject($ticketId, $this->uid, $reason);
        $this->apiSuccess();

    }

    public function _DO_conf()
    {
        $seriesSn = \input::get('series_sn')->value();

        $conf = servLessonSeries::sole($this->platform)->conf($seriesSn);
        $activityConf = servLessonSeries::sole($this->platform)->activityConf($seriesSn);
//        $this->apiSuccess($activityConf);
        \view::tpl('page', [
            'page' => 'lesson/series-conf',
        ])->with('conf', json_encode($conf), false)
            ->with('activity_conf', json_encode($activityConf), false)
            ->with('series_sn', $seriesSn);
    }

    public function _POST_conf()
    {
        $conf = $this->apiPOST('conf');
        $seriesSn = $this->apiPOST('series_sn');
//        $conf = str_replace("\n", '', $conf);
//        $conf = str_replace(" ", '', $conf);
        if (!$this->is_json($conf)) {
            $this->apiFailure(['1.1', '配置不是正确的json格式']);
        }
        $ret = servLessonSeries::sole($this->platform)->updateConf($seriesSn, $conf);
        if ($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(['1.2', '更新失败']);
    }

    public function _POST_activityConf()
    {
        $conf = $this->apiPOST('conf');
        $seriesSn = $this->apiPOST('series_sn');
        $conf = str_replace("\n", '', $conf);
        $conf = str_replace(" ", '', $conf);
        if (!$this->is_json($conf)) {
            $this->apiFailure(['1.1', '配置不是正确的json格式']);
        }
        $ret = servLessonSeries::sole($this->platform)->updateActivityConf($seriesSn, $conf);
        if ($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(['1.2', '更新失败']);
    }

    public function _POST_edit()
    {
        $seriesSn = $this->apiPOST('sn');
        $params = $this->apiRequest(null);
        $cover = $_FILES['cover']['tmp_name'] ?? null;
        $banner = $_FILES['banner']['tmp_name'] ?? null;

        $ret = servLessonSeries::sole($this->platform)->updateByparams($seriesSn, $params, $cover, $banner);
        if ($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(['1.3', '更新失败']);
    }

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}