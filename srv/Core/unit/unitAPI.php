<?php


namespace Core;


trait unitAPI
{
    private $apiParams;
    private $apiResponse;

    protected $apiHold = false;

    protected function apiResponse($error, $message, $data = null, $halt = true)
    {
        header('Cache-control: no-cache');
        header('Content-Type: application/json');
        $this->apiResponse = [
            'error' => (string)$error,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($this->apiResponse);
        $ts = $_SERVER['REQUEST_TIME_FLOAT'];
        $te = microtime(true);
        $tc = $te - $ts;
        $content = [
            'USN' => $this->usn ?? null,
            'URI' => $this->_URI_,
            'Params' => $this->apiParams,
            'Response' => $this->apiResponse,
            'TimeCost' => $tc,
        ];
        $conf = \config::load('boot', 'debug');
        $slow = $conf["api.slow.$this->_WAY_"] ?? $conf['api.slow'] ?? null;
        $show = array_merge($conf["api.show"]??[], $conf["api.show.$this->_WAY_"] ?? []);
        if ($slow && $tc*1000 > $slow) {
            foreach ($show as $k => $v) {
                if (!$v) {
                    unset($content[$k]);
                }
            }
            \output::debug('API', $content, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        }
        if ($halt == true && $this->apiHold == false) {
            exit;
        }
    }

    protected function apiSuccess($data = null, $halt = true)
    {
        $this->apiResponse(0, 'success', $data, $halt);
    }

    protected function apiFailure($error, array $args = [], $data = null, $halt = true)
    {
        $this->apiResponse($error[0], sprintf($error[1], ...$args), $data ?? $args, $halt);
    }

    protected function apiRequest($name, $default = null, $method = null)
    {
        if (empty($this->apiParams)) {
            $_method = '_' . ($method ?? $_SERVER['REQUEST_METHOD']);
            if ($_method == '_GET') {
                $this->apiParams = $_GET;
            } else {
                $input = file_get_contents('php://input');
                list($cType) = explode(';', $_SERVER['CONTENT_TYPE']);
                switch ($cType) {
                    case 'application/json':
                        $this->apiParams = json_decode($input, true);
                        break;
                    case 'application/x-www-form-urlencoded':
                        parse_str($input, $this->apiParams);
                        break;
                    default:
                        if (empty($GLOBALS[$_method])) {
                            $this->apiParams = json_decode($input, true);
                            if (json_last_error()) {
                                parse_str($input, $this->apiParams);
                            }
                        } else {
                            $this->apiParams = $GLOBALS[$_method];
                        }
                }
            }
        }


        if ($name == null) {
            return $this->apiParams;
        } else {
            $param = $this->apiParams[$name] ?? $default;
        }

        if ($param === null) {
            $this->apiFailure(['-1', "missing parameter `%s`"], [$name]);
        }
        return $param;
    }

    protected function apiGET($name, $default = null)
    {
        return $this->apiRequest($name, $default, 'GET');
    }

    protected function apiPOST($name, $default = null)
    {
        return $this->apiRequest($name, $default, 'POST');
    }

    protected function apiDELETE($name, $default = null)
    {
        return $this->apiRequest($name, $default, 'DELETE');
    }


    /*
     * todo 补充调试方法
    protected function apiDebug($item = self::DEBUG_REQUEST, $slot=DEBUG_SLOT_TEMP)
    {
        $content = "$_SERVER[REQUEST_METHOD] $this->_version_$this->_WAY_";
        if ($item & self::DEBUG_REQUEST) {
            $content .= '?'.json_encode($this->apiParams);
        }
        if ($item & self::DEBUG_RESPONSE) {
            $content .= ":$this->response";
        }

        \output::debug('Client-API', $content, $slot, DEBUG_REPORT_LOG);
    }
    */
}