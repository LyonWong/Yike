<?php


namespace _\stats;


use _\dataSole;
use Core\unitInstance;

class dataPeriod extends dataSole
{
    use unitInstance;

    const TABLE = 'stats_period';

    const PERIOD_DAILY = 1;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function appends($period, $data, $tms)
    {
        $values = [];
        foreach ($data as $row) {
            $values[] = [
                $period,
                $row['dom'],
                $row['idx'],
                $row['val'],
                $tms
            ];
        }
        $this->mysql->insertBatch($this->TABLE, ['i_period', 'dom', 'idx', 'val', 'tms'], $values);
    }

}