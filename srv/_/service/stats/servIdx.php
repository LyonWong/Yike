<?php


namespace _\stats;


use _\config;

class servIdx
{
    private static $_inited = false;
    private static $posMap = [];
    private static $keyMap = [];

    private static function _init()
    {
        if (self::$_inited == true) {
            return;
        }
        $idx = config::load('stats-idx');
        $_ = $idx['_'];
        unset($idx['_']);
        foreach ($idx as $section => $item)
        {
            foreach ($item['idx'] as $p => $k) {
                foreach ($_ as $_k => $_p) {
                    $pos = ($item['slot'] << 24) + ($p << 8) + $_p;
                    $key = "$section.$k.$_k";
                    self::$posMap[$pos] = $key;
                    self::$keyMap[$key] = $pos;
                }
            }
        }
        self::$_inited = true;

    }

    public static function key2pos($key)
    {
        self::_init();
        return self::$keyMap[$key] ?? null;
    }

    public static function pos2key($pos)
    {
        self::_init();
        return self::$posMap[$pos] ?? null;
    }

    public static function boost($raw)
    {
        $data = [];
        foreach ($raw as $idx => $val) {
            $idx = servIdx::pos2key($idx);
            $data[$idx] = $val;
        }

        if ($data['lesson.rate.count'] ?? 0) {
            $data['lesson.rate.avg'] = round($data['lesson.rate.sum'] / $data['lesson.rate.count'], 1);
        }
        //金额从分转换成元
        foreach ([
            'lesson.income.sum',
            'lesson.refund.sum',
            'lesson.payoff.sum',
            'teacher.income.sum',
            'teacher.refund.sum',
                 ] as $idx) {
            if ($data[$idx] ?? 0) {
                $data[$idx] /= 100;
            }
        }
        return $data;

    }


}