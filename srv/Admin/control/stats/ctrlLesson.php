<?php


namespace Admin\stats;


use _\servOrigin;
use Admin\unitLessonQuery;
use Teacher\servStats;

class ctrlLesson extends ctrl_
{
    public function _DO_()
    {
        $stats = servTimely::sole($this->platform)->lesson();
        $unitQuery = new unitLessonQuery([]);
        $profiles = \Admin\servLesson::sole($this->platform)->map($unitQuery);
        \view::tpl('page', [
            'page' => 'stats/lesson',
            'stats' => $stats,
        ])->with('profiles', $profiles);
    }

    public function _DO_origin()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $originId = \input::get('origin_id')->value();
        $tier = servOrigin::sole($this->platform)->tier($originId);
        $data = servStats::sole($this->platform)->origin($lessonSn, $originId);
        \view::tpl('page', [
            'page' => 'stats/lesson-origin',
            'query' => [
                'lesson_sn' => $lessonSn
            ],
        ])
            ->with('tier', $tier)
            ->with('data', $data);

        \output::debug('console', [$lessonSn, $originId, $data], 1, DEBUG_REPORT_JSC);
    }

}