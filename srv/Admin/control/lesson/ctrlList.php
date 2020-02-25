<?php


namespace Admin\lesson;


use _\dataLessonSeries;
use Admin\dataUser;
use Admin\servLesson;
use Admin\unitLessonQuery;
use Admin\unitQueryLessonList;
use Admin\wdgtLang;

class ctrlList extends ctrl_
{
    protected $scopeKey = 'lesson-list';

    public function _DO_()
    {
        $query = unitQueryLessonList::init($_GET);
        $query->dateStart = strToDate($query->dateStart, 'Y-m-d');
        $query->dateEnd = strToDate($query->dateEnd, 'Y-m-d');
        \view::tpl('page', [
            'page' => 'lesson/lesson-list',
            'query' => $query,
            'form_map' => servLesson::FORM_MAP,
            'step_map' => servLesson::STEP_MAP,
        ]);
    }

    public function _POST_step()
    {
        $lessonSn = $this->apiPOST('name');
        $iStep  = $this->apiPOST('value');
        $res = servLesson::sole($this->platform)->step($lessonSn, $iStep);
        $this->apiSuccess($res);
    }

    public function _GET_data()
    {
        $query = unitQueryLessonList::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servLesson::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        foreach ($res['pages'] as &$item) {
            $name = dataUser::sole($this->platform)->fetchOne(['id' => $item['tuid']], 'name', 'name');
            $item['isPublic'] = $item['i_form']>0 ? 1: 0;
            $item['plan'] = json_decode($item['plan'],true);
            $categoryCheck = json_decode($item['extra'], true)['category_check'] ?? 0;
            $categoryCheck = $categoryCheck ? '已勾选' : '未勾选';
            $seriesName = $item['category'] ? dataLessonSeries::sole($this->platform)->fetchOne(['sn'=>$item['category']],'name','name') : '';
            $item = [
                $item['id'],
                $item['sn'],
                '<a href="/lesson/detail?lesson_sn=' . $item['sn'] . '">' . $item['title'] . '</a>',
                $seriesName ? '<a href="/lesson/series-detail?series_sn=' . $item['category'] . '">' . $seriesName . '</a>  '.$categoryCheck:'',
                $item['plan']['dtm_start'],
                '<a href="/teacher/detail?tuid=' . $item['tuid'] . '">' . $name . '</a>',
                servLesson::FORM_MAP[abs($item['i_form'])],
                $item['isPublic']? '公开':'隐身',
                '￥'.$item['price'] /100,
                '<a onclick="edit()" data-type="select2" data-pk="1" data-name="'.$item['sn'].'" data-value="'.$item['i_step'].'" 
                  data-url="/lesson/list-step.api" 
                  data-original-title="Select groups" 
                  class="step editable editable-click" 
                  style="background-color: rgba(0, 0, 0, 0);"> '.wdgtLang::dict(servLesson::STEP_MAP[$item['i_step']]).'
                  </a>',
                '<a href="./online?lesson_sn='.$item['sn'].'" target="_blank">
                                    在线
                                </a>
                                <a href="./detail-inspect?lesson_sn='.$item['sn'].'" target="_blank">
                                    课堂
                                </a>
                                <a href="./share?lesson_sn='.$item['sn'].'" target="_blank">
                                    邀请
                                </a>
                                <a href="/lesson/log-teacher?lesson_sn='.$item['sn'].'" target="_blank">
                                    操作日志
                                </a>
                                <a href="/lesson/log-student?lessonField=sn&lessonValue='.$item['sn'].'" target="_blank">
                                    学员日志
                                </a>
                                <a href="/lesson/detail-conf?lesson_sn='.$item['sn'].'">配置</a>
                                <a href="/lesson/record?lesson_sn='.$item['sn'].'" target="_blank">课程内容</a>
                                <a href="/lesson/prepare?lesson_sn='.$item['sn'].'" target="_blank">备课内容</a>',

                $item['tms_create'],

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