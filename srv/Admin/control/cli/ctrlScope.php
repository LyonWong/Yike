<?php


namespace Admin\cli;


use Admin\dataScopeList;

class ctrlScope extends ctrl_
{
    public function _DO_set($scope)
    {
        $key = \input::cli('key')->value(true);
        $name = \input::cli('name', $key)->value();
        $type = \input::cli('type', 0)->toInt();
        $depth = \input::cli('depth', substr_count($key, '-') + 1)->toInt();
        $rank = \input::cli('rank', 0)->toInt();
        $path = \input::cli('path')->value();

        $data = [
            'key' => $key,
            'name' => $name,
            'type' => $type,
            'depth' => $depth,
            'rank' => $rank,
            'path' => $path,
        ];
        $id = dataScopeList::inst($scope)->append($data);
        echo "Auth ID: $id\n";
    }

    public function _DO_load($scope)
    {
        $list = \config::load("scope/$scope");

        foreach ($list as $key => &$val) {
            $val['key'] = $key;
            $val['type'] = $val['type'] ?? 0;
            $val['depth'] = $val['depth'] ?? (substr_count($key, '-') + 1);
            $val['rank'] = $val['rank'] ?? 0;
        }

        $dataScope = dataScopeList::inst($scope);
        $_list = $dataScope->fetchList();

        foreach ($_list as $_item) {
            $_key = $_item['key'];
            if (isset($list[$_key])) {
                $dataScope->updateById($_item['id'], $list[$_key]);
                unset($list[$_key]);
            } else {
                $dataScope->deleteById($_item['id']);
            }
        }

        foreach ($list as $item) {
            $dataScope->append($item);
        }
    }

    public function _DO_show($scope)
    {
        $list = dataScopeList::inst($scope)->fetchList();
        $show = array_column($list, 'key', 'id');
        print_r($show);
    }

}