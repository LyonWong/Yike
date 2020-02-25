<?php


namespace _;


class view extends \view
{
    public static function src($path, $space = __NAMESPACE__)
    {
        return parent::src($path, $space);
    }

}