<?php


namespace Admin;


class unitQueryRefund
{
    public $dateField = 'create';

    public $dateStart = '-15 days';

    public $dateEnd = 'today';

    public $userField = 'sn';

    public $userValue = null;

    public $orderField = 'sn';

    public $orderValue = null;

    public $lessonField = 'sn';

    public $lessonValue = null;

    public $status = '*';

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

    public function toString()
    {
        $vars = get_class_vars(__CLASS__);
        foreach ($vars as $key => &$val) {
            $val = $this->$key;
        }
        return http_build_query($vars);
    }

}