<?php


namespace _;


use Core\unitInstance;

class dataPayoff_ extends dataSole
{
    use unitInstance;

    const TABLE = 'payoff_';

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        $this->TABLE = self::TABLE;
        parent::__construct($platform);
    }

}