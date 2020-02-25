<?php

namespace Core;

trait unitTree
{
    public static function treeProduce(array $treePaths, $forkSign='-', $leafSign='!')
    {
        $tree = [];
        foreach ($treePaths as $path => $leaf) {
            $_tree = &$tree;
            foreach (explode($forkSign,$path) as $chip) {
                if (empty ($_tree[$chip])) {
                    $_tree[$chip] = [];
                }
                $_tree = &$_tree[$chip];
            }
            $_tree[$leafSign] = $leaf;
        }
        return $tree;
    }

    public static function treeReduce(array $tree, $forkSign='-', $leafSign='!')
    {
        $map = [];
        foreach ($tree as $key => $subTree) {
            if (is_array($subTree)) {
                $_map = self::treeReduce($subTree, $forkSign, $leafSign);
                foreach ($_map as $_key => $_val) {
                    $i = ($_key == $leafSign) ? $key : $key.$forkSign.$_key;
                    $map[$i] = $_val;
                }
            } else {
                $map[$key] = $subTree;
            }
        }
        return $map;
    }

    public static function treeMerge(){}

}