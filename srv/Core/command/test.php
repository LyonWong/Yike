<?php
require dirname(__DIR__) . '/../Core/bootstrap.php';


const BOOT_MODE = BOOT_MODE_TEST;

$space = \input::cli('space')->value(true);
boot::init($space);
