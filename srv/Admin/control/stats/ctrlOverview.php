<?php


namespace Admin\stats;


class ctrlOverview extends ctrl_
{
    public function _DO_()
    {
        $dateStart = \input::get('dateStart', '-15 days')->toDate();
        $dateEnd = \input::get('dateEnd', '-1 days')->toDate();
        $query = [
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ];
        $summary = servTimely::sole($this->platform)->summary();
        $period = servPeriod::sole($this->platform)->summary($dateStart, $dateEnd);
        \view::tpl('page', [
            'page' => 'stats/overview'
        ])
            ->with('query', $query)
            ->with('summary', $summary)
            ->with('period', $period);
        $this->debugOnJSC('summary', $summary);
        $this->debugOnJSC('period', $period);
    }

}