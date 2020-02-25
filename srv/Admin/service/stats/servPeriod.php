<?php


namespace Admin\stats;


use _\stats\dataPeriod;
use _\stats\servIdx;
use Core\unitInstance;

class servPeriod extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function summary($dateStart, $dateEnd)
    {
        $res = dataPeriod::sole($this->platform)->fetchAll([
            'i_period' => dataPeriod::PERIOD_DAILY,
            'dom' => '*',
            'tms between ? and ?' => [$dateStart, $dateEnd],
        ], '*');

        $data = [];
        foreach ($res as $row) {
            $date = strToDate($row['tms'], 'Y-m-d');
            $data[$date][$row['idx']] = $row['val'];
        }

        foreach ($data as $date => &$item) {
            $item = servIdx::boost($item);
        }

        return $data;
    }

}