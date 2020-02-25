<?php


namespace Admin;



class wdgtPicker
{

    public static function dateDiff($dateBase, $dateDiff)
    {
        $attrs = [
            'dateBase' => $dateBase,
            'dateDiff' => $dateDiff,
        ];
        return \view::tpl('/component/picker-dateDiff', $attrs)->res();
    }

    public static function dateRange($dateStart, $dateEnd)
    {
        $attrs = [
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
        ];
        return \view::tpl('/component/picker-dateRange', $attrs)->res();
    }
    
    public static function source($sourceLid=0, $sourceIndent=null)
    {
        return \view::tpl('/component/picker-source', [
            'sourceLid'=>$sourceLid,
            'sourceIndent' => $sourceIndent,
        ])->res();
    }

    public static function fields($prefix, $field, $value)
    {
        return \view::tpl('/component/picker-fields', [
            'prefix' => $prefix,
            'field' => $field,
            'value' => $value,
        ])->res();
    }



}