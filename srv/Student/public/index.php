<?php
require dirname(__DIR__) . '/../Core/bootstrap.php';


define('PUBLIC_PATH', realpath(dirname(__FILE__)));
const BOOT_MODE = BOOT_MODE_CGI;

boot::init('Student');

try {
    boot::run($_SERVER['REQUEST_URI']);
} catch (coreException $e) {
    list($EID, $code) = coreException::parseCode($e->getCode(), true);
    if ($EID == viewException::EID) {
        header("Status: $code");
    }
    $info = coreException::makeInfo($e);
    \output::debug('student', $info);
}
