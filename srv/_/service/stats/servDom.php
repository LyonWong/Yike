<?php


namespace _\stats;


class servDom
{
    const ZONE_LESSON = 'L';
    const ZONE_ORIGIN = 'O';
    const ZONE_TEACHER = 'T';

    public static function build($arr)
    {
        return http_build_query($arr);
    }

    public static function builds($arr)
    {
        $res = [];
        do {
            $res[] = http_build_query($arr);
            array_pop($arr);
        } while ($arr);
        $res[] = '*';
        return array_reverse($res);
    }

    public static function parse($str)
    {
        parse_str($str, $arr);
        return $arr;
    }

}