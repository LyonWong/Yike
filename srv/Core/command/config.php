<?php
require __DIR__ . '/../bootstrap.php';

$space = \input::cli('space')->value(true);
$file = \input::cli('file')->value(true);
$section = \input::cli('section')->value();
$item = \input::cli('item')->value();

const BOOT_MODE = BOOT_MODE_CLI;
boot::init($space);

$config = config::load($file);

if (!$section) {
    $sectionList = array_keys($config);
    echo implode(' ', $sectionList);
    exit;
}

if (!$item) {
    $itemList = array_keys($config[$section]);
    echo implode(' ', $itemList);
} else {
    if (isset($config[$section][$item])) {
        echo $config[$section][$item];
    }
}



