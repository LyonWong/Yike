<?php


namespace Admin;


class unitQueryBoard
{
    public $dateField = 'create';

    public $dateStart = '-15 days';

    public $dateEnd = 'today';

    public $userField = 'sn';

    public $userValue = null;

    public $lessonField = 'sn';

    public $lessonValue = null;

    public $type = '*';

    public $paySn = null;

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