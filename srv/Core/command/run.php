<?php
require dirname(__DIR__) . '/../Core/bootstrap.php';


const BOOT_MODE = BOOT_MODE_CLI;

$space = \input::cli('space')->value(true);
boot::init($space);
try {
    boot::run(input::cli(-1)->value());
} catch (coreException $e) {
    echo coreException::makeInfo($e);
}
