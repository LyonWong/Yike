<?php


namespace Admin;


class unitQueryMoney
{
    public $dateField = 'tms';

    public $dateStart = '-15 days';

    public $dateEnd = 'today';

    public $item = 'income';

    public $uid = null;


    public function __construct($params)
    {
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }
    }

    public static function init($params=[])
    {
        return new self($params);
    }

}