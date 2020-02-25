<?php


namespace _;


class unitLessonPromote
{

    public $iType;

    public $seriesId = 0;

    public $discount = 0;

    public $commission = 0;

    public $remark='';

    public $quantity;

    public $expire;

    public $duration;

    public $payoff;

    public $price;

    public $args=[];

    public static function inst($iType)
    {
        return new self($iType);
    }

    public function __construct($iType)
    {
        $this->iType = $iType;
    }

}