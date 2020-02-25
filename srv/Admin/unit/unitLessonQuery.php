<?php


namespace Admin;


use Core\unitQuery;

class unitLessonQuery extends unitQuery
{
    const LIST = ['tusn'];

    public $tusn;

    public function toWhere()
    {
        $where = [];
        foreach (self::LIST as $item) {
            if ($this->$item) {
                $where[$item] = $this->$item;
            }
        }
        return $where;
    }

}