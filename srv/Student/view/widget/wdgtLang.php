<?php


namespace Student;

use Core\library\Language;

class wdgtLang
{
    public static function base($key)
    {
        return Language::sole(servSession::$lang, ['base'])->refer($key);
    }

    public static function dict($key)
    {
        return Language::sole(servSession::$lang, ['base', 'dict'])->refer($key);
    }
}