<?php

namespace _;

class config extends \config
{
    public static function load($file, $section = null, $item = null, $default = null, $space=__NAMESPACE__)
    {
        return parent::load($file, $section, $item, $default, $space);
    }
}
