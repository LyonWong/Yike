<?php

define('PATH_ROOT', dirname(__DIR__));

define('PATH_CORE', PATH_ROOT . '/Core');

foreach (glob(PATH_CORE.'/basic/*.php') as $baseFile) {
    require_once $baseFile;
}


class boot
{
    static $REQUEST_TIME;

    public static function run($URI)
    {
        $res = router::request($URI);
        if (!class_exists($res['ctrl'])) {
            throw new coreException("Can't find controller `$res[ctrl]`");
        }
        $controller = new $res['ctrl'];
        if (!method_exists($controller, 'run')) {
            throw new coreException("Can't run controller `$res[ctrl]`");
        }
        if (isset ($res['attr'])) {
            foreach ($res['attr'] as $key => $val) {
                $controller->{'_'.$key.'_'} = $val;
            }
        }
        if (method_exists($controller, 'runBefore') &&
            $controller->runBefore() === false
        ) {
            exit;
        }
        self::$REQUEST_TIME = $_SERVER['REQUEST_TIME'] ?? time();
        $controller->run(...$res['args']);
        if (method_exists($controller, 'runBehind') &&
            $controller->runBehind() === false
        ) {
            exit;
        }
    }

    public static function init($space)
    {
        define('SPACE', $space);
        define('PATH_SPACE', PATH_ROOT."/$space");
        defined('LF') || define('LF', "\n");
        define('BOOT_ENV', config::load('boot', 'system', 'env', BOOT_ENV_LIVE));
        define('BOOT_TIMESTAMP', $_SERVER['REQUEST_TIME']);
        define('COOKIE_SESSION', config::load('boot', 'public', 'cookie.session', 'sess'));
        defined('DEBUG_MASK') || define('DEBUG_MASK', config::load('boot', 'debug', 'mask', 0));
        defined('DEBUG_REPORT') || define('DEBUG_REPORT', config::load('boot', 'debug', 'report.'.BOOT_MODE, config::load('boot', 'debug', 'report', 0)));
        $_SERVER['REQUEST_SCHEME'] = $_SERVER['HTTP_X_CLIENT_PROTO'] ?? $_SERVER['REQUEST_SCHEME'] ?? null;
        spl_autoload_register('self::autoload');
        input::init();
    }

    public static function autoload($name)
    {
        $routemap = array_merge([
            'ctrl' => 'control',
            'serv' => 'service',
            'data' => 'data',
            'unit' => 'unit',
            'wdgt' => 'view/widget',
        ], config::load('boot', 'routemap', null, []));
        $frags = explode('\\', $name);
        $cls = array_pop($frags);
        if (preg_match('#([a-z]+)([A-Z_]\w*)?#', $cls, $matches)
            && isset($routemap[$matches[1]])
        ) {
            $frags[0] .= '/' . $routemap[$matches[1]];
            $cls = $matches[0];
        }
        $path = implode('/', $frags);
        $path .= '/' . $cls;
        $file = realpath(PATH_ROOT."/$path.php");
        if ($file) {
            require_once $file;
        }
    }
}

