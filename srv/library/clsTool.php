<?php


class clsTool
{
    const STR_NUMBER = '0123456789';
    const STR_FORMAL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const STR_BASE64 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
    const STR_SECRET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+=_-~!@#$%^&*(),./<>?';

    public static function genSecret($length, $base = self::STR_SECRET)
    {
        $secret = '';
        $maxLength = strlen($base) - 1;
        while ($length--) {
            $offset = rand(0, $maxLength);
            $secret .= $base[$offset];
        }
        return $secret;
    }

    public static function timeRound($timestamp)
    {
        return strtotime(date('Ymd', $timestamp));
    }

    public static function timeSeries($tmsStart, $tmsEnd, $format, $step)
    {
        $tmi = strtotime($tmsStart);
        $deadline = strtotime($tmsEnd);
        $series = [];
        do {
            $series[] = date($format, $tmi);
            $tmi += $step;
        } while ($tmi <= $deadline);
        return $series;
    }

    public static function IPcheck(string $IP, $allowed)
    {
        if (is_array($allowed)) {
            foreach ($allowed as $_allowed) {
                if (self::IPcheck($IP, $_allowed)) {
                    return true;
                }
            }
            return false;
        } else {
            list($host, $slot) = explode('/', "$allowed/32");
            $mask = (1 << 32) - (1 << (32 - $slot));
            return (ip2long($IP) & $mask) == (ip2long($host) & $mask);
        }
    }

    public static function IP2Long(string $IP)
    {
        return sprintf('%u', ip2long($IP));
    }

    public static function IP2Country(string $IP)
    {
        static $AoIDN;
        if (empty($AoIDN)) {
            require_once '17monipdb/IP.class.php';
            $AoIDN = require_once('data/AoIDN.php');
        }
        $res = IP::find($IP);
        if ($res == 'N/A') {
            return null;
        } else {
            return $AoIDN[$res[0]] ?? null;
        }
    }

    public static function parseCSV($file)
    {
        $fp = fopen($file, 'r');
        $head = fgets($fp);
        $heads = str_getcsv($head);
        $data = [];
        while ($row = fgets($fp)) {
            $rows = str_getcsv($row);
            $data[] = array_combine($heads, $rows);
        }
        return $data;
    }

    public static function timeEncode($time)
    {
        return base_convert(strtotime($time), 10, 32);
    }

    public static function timeDecode($base32, $format = 'Y-m-d H:i:s')
    {
        return date($format, base_convert($base32, 32, 10));
    }

    public static function xmlEncode($data, $cdata = false)
    {
        if (!is_array($data)) {
            if ($cdata) {
                return "<![CDATA[$data]]>";
            } else {
                return $data;
            }
        }
        $xml = '';
        foreach ($data as $key => $val) {
            $xml_ = self::xmlEncode($val, $cdata);
            if (is_int($key)) {
                $xml .= $xml_;
            } else {
                $xml .= "<$key>$xml_</$key>\r\n";
            }
        }
        return $xml;
    }

    public static function xmlDecode($xml, $cdata = false)
    {
        libxml_disable_entity_loader(true);
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', $cdata ? LIBXML_NOCDATA: 0);
        $data = json_decode(json_encode($obj), true);
        return array_filter($data, function ($v) {
            return $v !== [];
        });
    }

    /**
     * 获取客户端的 IP
     *
     * @param boolean $ip2long 是否转换成为整形
     *
     * @return int|string
     */
    public static function getClientIp($ip2long = false)
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER ['HTTP_X_REAL_IP'];
            } else if (isset($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
                $ip = array_pop(explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']));
            } elseif (isset($_SERVER ['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER ['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER ['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_REAL_IP')) {
                $ip = getenv('HTTP_X_REAL_IP');
            } else if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = array_pop(explode(',', getenv('HTTP_X_FORWARDED_FOR')));
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }

        return $ip2long ? sprintf("%u", ip2long($ip)) : $ip;
    }

    /**
     * 验证手机号
     *
     * @param string $phone
     * @return boolean
     */
    public static function isMobile($phone)
    {
        return (bool)(preg_match('/^(\+86)?(1\d{10})$/', $phone));
    }

    public static function isMobileRequest()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser = 0;
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if ($mobile_browser > 0)
            return true;
        else
            return false;
    }
}


