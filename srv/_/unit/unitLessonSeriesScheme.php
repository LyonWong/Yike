<?php


namespace _;


class unitLessonSeriesScheme
{
    public $price;

    public $share;

    public $name;


    public static function inst()
    {
        return new self;
    }

    public function encode()
    {
        return json_encode([
            'price' => $this->price,
            'share' => $this->share,
        ]);
    }

    public static function parse($obj)
    {
        $data = json_decode($obj, true);
        $inst = self::inst();
        foreach($data as $key => $val) {
            $inst->$key = $val;
        }
        return $inst;
    }

}