<?php

/**
 * Created by PhpStorm.
 * Author: LyonWong
 * Date: 2014-07-28
 */

class input
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    private static $data;

    private static $method;

    public static function init()
    {
        self::$data = $GLOBALS;
        if (isset ($_POST['_METHOD_'])) {
            self::$method = $_POST['_METHOD_'];
            $method = '_'.self::$method;
            self::$data[$method] = $_POST;
            unset (self::$data[$method]['_METHOD_']);
        } elseif (isset($_SERVER['REQUEST_METHOD'])) {
            self::$method = $_SERVER['REQUEST_METHOD'];
            $method = '_'.self::$method;
            parse_str(phpStream::input(), $_data);
            self::$data[$method] = array_merge(self::$data[$method] ?? [], $_data);
        } else {
            self::$method = 'CLI';
        }
    }

    public static function method()
    {
        return self::$method;
    }

    public static function cli($name, $default=null)
    {
        if (empty(self::$data['_CLI'])) {
            global $argv;
            $data['_CLI'] = $argv ?: [];
            self::$data['_CLI-named'] = parseArgv($data['_CLI'], self::$data['_CLI-naked']);
        }
        if (is_int($name)) { // args with int offset
            $i = ($name >=0) ? $name : $name + count(self::$data['_CLI-naked']);
            return self::makeInput('_CLI-naked', $i, $default);
        } else { // args with a name
            return self::makeInput('_CLI-named', $name, $default);
        }
    }

    public static function get($name, $default = null)
    {
        return self::makeInput('_GET', $name, $default);
    }

    public static function post($name, $default = null)
    {
        return self::makeInput('_POST', $name, $default);
    }

    public static function postJson($name, $default = null)
    {
        if (empty(self::$data['_POST_JSON'])) {
            $input = phpStream::input();
            self::$data['_POST_JSON'] = json_decode($input, true);
        }
        return self::makeInput('_POST_JSON', $name, $default);
    }

    public static function postRawData($default = null)
    {
        return self::makeInput('HTTP_RAW_POST_DATA', null, $default);
    }

    public static function put($name, $default = null)
    {
        return self::makeInput('_PUT', $name, $default);
    }

    public static function delete($name, $default=null)
    {
        return self::makeInput('_DELETE', $name, $default);
    }

    public static function cookie($name, $default = null)
    {
        static $prefix;
        if (empty($prefix)) {
            $prefix = config::load('boot', 'public', 'cookie.prefix');
        }
        $name = $prefix . $name;
        return self::makeInput('_COOKIE', $name, $default);
    }

    public static function session($name, $default = null)
    {
        return self::makeInput('_SESSION', $name, $default);
    }

    public static function raw($default=null) {
        $input = file_get_contents('php://input');
        $obj = objInput::instance('_input_', $input)->setDefault($default);
        return $obj;
    }

    public static function ip($toInt=false)
    {
		$keys = ['HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_REMOTE_HOST', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        $ip = false;
        foreach ($keys as $key) {
            if (isset ($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                break;
            }
        }
        $ipInt = ip2long($ip);
        $ip = $toInt ? sprintf('%u', $ipInt) : long2ip($ipInt);
        return $ip;
    }


    /**
     * @param $origins
     * @param null $name
     * @param null $default
     * @return objInput
     */
    private static function makeInput($origins, $name = NULL, $default = NULL)
    {
        $origins = (array)$origins;
        $val = NULL;
        foreach ($origins as $origin) {
            if (isset (self::$data[$origin])) {
                if ($name === NULL) {
                    $val = self::$data[$origin];
                } elseif (isset (self::$data[$origin][$name])) {
                    $val = self::$data[$origin][$name];
                }
                break;
            }
        }
        $obj = objInput::instance($name, $val)->setDefault($default);
        return $obj;
    }

}

class objInput
{
    private $name;
    private $value;

    public static function instance($name, $value = null)
    {
        $inst = new self;
        $inst->name = $name;
        $inst->value = $value;
        return $inst;
    }

    public function name()
    {
        return $this->name;
    }

    public function value($required=false)
    {
        if ($required == true && $this->value === null) {
            coreException::halt("Parameter `$this->name` is required.");
        }
        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setDefault($value)
    {
        $this->value = $this->value ?? $value;
        return $this;
    }

    public function filter($filter, $params = NULL)
    {
        if (!is_array($params)) {
            $params = [$this->value, $params];
        } else {
            array_unshift($params, $this->value);
        }
        $this->value = call_user_func_array($filter, $params);
        return $this;
    }

    public function toInt()
    {
        return intval($this->value);
    }

    public function toBool()
    {
        return boolval($this->value);
    }

    public function toFloat()
    {
        return floatval($this->value);
    }

    public function toDate($format='Y-m-d')
    {
        return date($format, strtotime($this->value));
    }

    public function toWord($format='\w')
    {
        return preg_replace("#[^$format]#", '', $this->value);
    }
}

class output
{
    private static $instance;

    private $pathLog;

    private $delayedLogs = [];

    private static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
            self::$instance->pathLog = config::load('boot', 'path', 'log', '/tmp');
        }
        return self::$instance;

    }

    public function __destruct()
    {
        foreach ($this->delayedLogs as $key => $messages) {
            foreach ($messages as $message) {
                self::log($key, $message);
            }
        }
    }

    public static function log($name, $content, $delay = false)
    {
        $inst = self::instance();
        if (is_array($content) || is_object($content)) {
            $content = json_encode($content,  JSON_UNESCAPED_UNICODE);
        }
        if ($delay == true) {
            $inst->delayedLogs[$name][] = $content;
        } else {
            $file = sprintf('%s/%s.log', $inst->pathLog, $name);
            $content = sprintf('[%s] %s', date('Y-m-d H:i:s'), $content . LF);
            file_put_contents($file, $content, FILE_APPEND);
        }
    }

    public static function debug($name, $content, $slot=1, $report=DEBUG_REPORT)
    {
        if (!($slot & DEBUG_MASK)) {
            return false;
        }
        if ($report & DEBUG_REPORT_STD) {
            echo "[DEBUG]$name: ";
            if (is_array($content) || is_object($content)) {
                $content = json_encode($content,  JSON_UNESCAPED_UNICODE);
            }
            print_r($content.LF);
        }
        if ($report & DEBUG_REPORT_LOG) {
            self::log("debug-$name", $content);
        }
        if ($report & DEBUG_REPORT_JSC) {
            echo "<script type='text/javascript'>console.log('[DEBUG]$name:');console.log(".json_encode($content,  JSON_UNESCAPED_UNICODE).");</script>";
        }
        return true;
    }

    public static function cookie($name, $value, $duration=null, $path=null, $domain=null)
    {
        static $prefix;
        if (empty($prefix)) {
            $prefix = config::load('boot', 'public', 'cookie.prefix');
        }
        $name = $prefix . $name;
        $expire = is_int($duration) ? time() + $duration : strtotime($duration);
        setcookie($name, $value, $expire, $path, $domain);
    }

    public static function session($name, $value)
    {

    }

    public static function csv($name, array $data)
    {
        header("Content-Type: text/csv");
        header("Content-Disposition:attachment;filename=$name.csv");
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($data as $row) {
            echo implode(',', $row)."\r\n";
        }
    }


}

class phpStream
{
    public static function input()
    {
        return file_get_contents('php://input');
    }

    public static function stdin()
    {
        return file_get_contents('php://stdin');
    }
}