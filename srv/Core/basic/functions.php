<?php

/**
 * Parse time string to date format
 * @param string $time
 * @param string $format
 * @param int|null $now
 * @return bool|string
 */
function strToDate($time, $format, $now = null)
{
    $tms = $now ? strtotime($time, $now) : strtotime($time);
    return date($format, $tms);
}

/**
 * Merge two or more array recursively, override on duplicate key
 * @param array $array1
 * @param array $_
 * @return mixed
 */
function arrayMergeForce(array $array1, array $_ = null)
{
    $arrays = func_get_args();
    $main = array_shift($arrays);
    foreach ($arrays as $array) {
        foreach ($array as $key => $val) {
            if (isset($main[$key]) && is_array($main[$key]) && is_array($val)) {
                $main[$key] = arrayMergeForce($main[$key], $val);
            } else {
                $main[$key] = $val;
            }
        }
    }
    return $main;
}

/**
 * Return value specified by index
 * @param $array
 * @param int $index
 * @return mixed|null
 */
function arrayFetch($array, $index = 0)
{
    if (!is_array($array)) {
        return false;
    }
    if (isset ($array[$index])) {
        $ret = $array[$index];
    } elseif (is_int($index)) {
        while ($index-- > 0) {
            next($array);
        }
        $ret = current($array);
    } else {
        $ret = null;
    }
    return $ret;
}

function arrayPosition(array $array, $index)
{
    $i = 0;
    $pos = false;
    foreach ($array as $key => $val) {
        if ($key == $index) {
            $pos = $i;
        }
        $i++;
    }
    return $pos;
}

function arrayFlatten(array $array, $glue = '-')
{
    $ret = [];
    foreach ($array as $key => $val) {
        if (is_array($val)) {
            $_ret = arrayFlatten($val);
            foreach ($_ret as $_key => $_val) {
                $i = ($_key == '0') ? $key : $key . $glue . $_key;
                $ret[$i] = $_val;
            }
        } else {
            $ret[$key] = $val;
        }
    }
    return $ret;
}

function arrayCuttle(array $array, $glue = '-')
{
    $ret = [];
    foreach ($array as $key => $v) {
        $item = [];
        $cur = &$item;
        foreach (explode($glue, $key) as $k) {
            $cur = [$k => $v];
            $cur = &$cur[$k];
        }
        $ret = array_merge_recursive($ret, $item);
    }
    return $ret;
}

function parseArgv(array $argv, &$nakedArgus = null)
{
    $argus = $nakedArgus = [];
    foreach ($argv as $val) {
        $prefix = substr($val, 0, 2);
        if ($prefix == '--' || $prefix == '~~') {
            if ($pe = strpos($val, '=')) {
                $name = substr($val, 2, $pe - 2);
                $value = substr($val, $pe + 1);
            } else {
                $name = substr($val, 2);
                $value = true;
            }
            $argus[$name] = $value;
        } else {
            $nakedArgus[] = $val;
        }
    }
    return $argus;
}

function encodeAttr(array $attrArray)
{
    $attr = '';
    foreach ($attrArray as $key => $val) {
        $attr .= "$key:$val;";
    }
    return trim($attr, ';');
}

function decodeAttr(string $attrString)
{
    $frags = explode(';', trim($attrString, ';'));
    $attrs = [];
    foreach ($frags as $item) {
        list($key, $val) = explode(':', $item);
        $attrs[$key] = $val;
    }
    return $attrs;
}
