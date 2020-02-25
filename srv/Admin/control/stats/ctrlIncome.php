<?php


namespace Admin\stats;


use _\servFinance;

class ctrlIncome extends ctrl_
{

    public function _DO_()
    {
        $dateStart = \input::get('dateStart', '-15 days')->toDate();
        $dateEnd = \input::get('dateEnd', '-1 days')->toDate();
        $groupBy = \input::get('groupBy', 'date')->value();
        $tsn = \input::get('tsn')->value();

        $query = [
            'groupBy' => $groupBy,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'tsn' => $tsn,
        ];
        $data = servFinance::sole($this->platform)->income($groupBy, $dateStart, $dateEnd, $tsn);

        \view::tpl('page', [
            'page' => 'stats/income',
            'query' => $query,
            'data' => $data
        ]);
    }

}