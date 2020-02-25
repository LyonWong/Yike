<?php


namespace Student;


use Core\unitQuery;

class unitLessonQuery extends unitQuery
{
    const _LIST_ = ['cursor', 'limit', 'tusn', 'sn'];

    public $cursor;

    public $limit;

    public $tusn;

    public $sn;

    public static function inst($params)
    {
        return new self($params);
    }

}