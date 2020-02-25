<?php


namespace _;


use Core\unitInstance;

class dataPayoff extends dataSole
{
    use unitInstance;

    const TABLE = 'payoff';

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