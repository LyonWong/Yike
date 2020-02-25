<?php


namespace _\stats;


use _\data;
use _\serv_;
use Core\unitInstance;

class servCron extends serv_
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

    public function dailyShot($date)
    {
        $dailyData = dataDaily::sole($this->platform, $date)->fetchAll(null, ['dom', 'idx', 'val']);
        if ($dailyData) {
            $tms = strToDate($date, 'Y-m-d 00:00:00');
            dataPeriod::sole($this->platform)->delete([
                'i_period' => dataPeriod::PERIOD_DAILY,
                'tms' => $tms
            ]);
            dataPeriod::sole($this->platform)->appends(dataPeriod::PERIOD_DAILY, $dailyData, $tms);
        }
    }

    public function clear($deadline='today')
    {
        $db = data::mysql();
        $tables = [];
        // 清理30天前的每日统计表
        $dailyPrefix = dataDaily::TABLE_PREFIX;
        $dailyTables = $db->run("show tables like '$dailyPrefix%'")->fetchAll(null, 0);
        $dTable = $dailyPrefix.strToDate("$deadline -30 days", 'Ymd');
        foreach ($dailyTables as $_table) {
            if ($_table < $dTable) {
                $db->run("drop table if exists `$_table`");
                $tables[dataDaily::TABLE_PREFIX][] = $_table;
            }
        }
        return $tables;
    }

}