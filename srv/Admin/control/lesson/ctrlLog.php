<?php


namespace Admin\lesson;


use _\config;
use _\dataLessonAccess;
use Admin\servLesson;
use Admin\servLessonAccess;
use Admin\servLessonProcess;
use Admin\servPromote;
use Admin\servUser;
use Admin\unitLessonAccessQuery;
use Admin\wdgtLang;
use Admin\wdgtPicker;
use Core\unitHttp;

class ctrlLog extends ctrl_
{
    use unitHttp;

    protected $scopeKey = 'lesson-log';

    public function _DO_()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $data = servLesson::sole($this->platform)->detail($lessonSn);
        \view::tpl('page', [
            'page' => 'lesson/detail',
            'step_map' => servLesson::STEP_MAP,
        ])->with('data', $data);
    }

    public function _GET_teacher()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $lesson = servLesson::sole($this->platform)->profile($lessonSn);
        $list = servLessonProcess::sole($this->platform)->list($lessonSn);
        \view::tpl('page', [
            'page' => 'lesson/teacher-log',
            'lesson' => $lesson,
        ])->with('list', $list);

        $this->apiSuccess($lesson);
    }

    public function _GET_student()
    {
        $query = unitLessonAccessQuery::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'lesson/student-log',
            'query' => $query
        ]);
    }

    public function _GET_data()
    {
        $query = unitLessonAccessQuery::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servLessonAccess::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $item['args'] = json_decode($item['args'], true);
            $lesson = servLesson::sole($this->platform)->detail($item['lesson_id'], 'id');
            $user = servUser::sole($this->platform)->uid2info($item['uid'], 'name');
            $iEvent = wdgtLang::status(servLessonAccess::ACCESS_MAP[$item['i_event']]);
            switch ($item['i_event']) {
                case dataLessonAccess::EVENT_RECEIVE:
                    $info = servPromote::sole($this->platform)->info($item['args']['promote']);
                    $user = servUser::sole($this->platform)->uid2info($info['uid'], 'name');
                    $commission = $info['commission'] / 100;
                    $discount = $info['discount'] / 100;
                    $more = "<a href='/operation/promote?psn=$info[sn]' target='_blank'>[$user[name]]的优惠券(折扣￥$discount,佣金￥$commission)</a>";
                    break;
                default:
                    $more = '';
                    break;
            }
            $item = [
                $item['id'],
                '<a href="/lesson/detail?lesson_sn=' . $lesson['sn'] . '">' . $lesson['title'] . '</a>',
                '<a href="/user/detail?uid=' . $item['uid'] . '">' . $user['name'] . '</a>',
                $iEvent,
                json_encode($item['args']),
                $item['tms'],
                $more,
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

}