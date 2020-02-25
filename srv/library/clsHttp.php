<?php


class clsHttp
{
    protected $ch;

    protected $infoItems = ['HTTP_CODE', 'TOTAL_TIME'];

    protected $options = [
        CURLOPT_RETURNTRANSFER => true,
    ];

    protected $contents = [];

    public static function inst(array $options = [])
    {
        $inst = new self($options);
        return $inst;
    }

    public function __construct(array $options = [])
    {
        $this->ch = curl_init();
        $this->setOptions($options);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->contents[] = 'TIME: ' . date('Y-m-d H:i:s');
    }

    public function __destruct()
    {
        $infos = $this->getInfo();
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
        $this->contents[] = 'INFO: ' . json_encode($infos);
        $content = implode("\t", $this->contents);
        $this->log($content);
    }

    public function get($URL, array $parms = [])
    {
        $URL = self::makeURL($URL, $parms);
        curl_setopt($this->ch, CURLOPT_URL, $URL);
        $this->contents[] = "GET: $URL";
        $res = curl_exec($this->ch);
        $err = curl_errno($this->ch);
        $this->contents[] = "RES: $res";
        $this->contents[] = "ERR: $err";
        return $res;
    }

    public function post($URL, $data)
    {
        $options = [
            CURLOPT_URL => $URL,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ];
        $data = is_string($data) ? $data : json_encode($data);
        $this->setOptions($options);
        $this->contents[] = "POST: $URL";
        $this->contents[] = 'DATA: ' . $data;
        $res = curl_exec($this->ch);
        $err = curl_errno($this->ch);
        $this->contents[] = "RES: $res";
        $this->contents[] = "ERR: $err";
        return $res;
    }

    public function request($method, $URL, $data)
    {
        $options = [
            CURLOPT_URL => $URL,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
        ];
        $this->setOptions($options);
        $this->contents[] = "$method: $URL";
        $this->contents[] = 'DATA: ' . (is_string($data) ? $data : json_encode($data));
        $res = curl_exec($this->ch);
        $this->contents[] = "RES: $res";
        return $res;
    }

    /**
     * If ever assigned the instance, please close the handle
     */
    public function close()
    {
        curl_close($this->ch);
    }


    public function exec()
    {
        $res = curl_exec($this->ch);
        $this->contents['Response'] = $res;
        $this->contents['Info'] = [
            'HttpCode' => curl_getinfo($this->ch, CURLINFO_HTTP_CODE),
            'TotalTime' => curl_getinfo($this->ch, CURLINFO_TOTAL_TIME),
        ];
        curl_close($this->ch);
        $this->log($this->contents);
        return $this;
    }

    public function setOptions($options)
    {
        curl_setopt_array($this->ch, $options);
        return $this;
    }

    public function setHeader(...$headers)
    {
        foreach ($headers as $header) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        }
        $this->contents[] = "HEADER: " . json_encode($headers);
        return $this;
    }

    public function setCookie(array $cookies)
    {
        $cookie = http_build_query($cookies, null, ';') . ' ';
        curl_setopt($this->ch, CURLOPT_COOKIE, $cookie);
        return $this;
    }

    public function setReferer($referer)
    {
        curl_setopt($this->ch, CURLOPT_REFERER, $referer);
        return $this;
    }

    public function setUserAgent($userAgent)
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, $userAgent);
        return $this;
    }


    public function getInfo($items = ['HTTP_CODE', 'TOTAL_TIME'])
    {
        $info = [];
        foreach ($items as $item) {
            $info[$item] = curl_getinfo($this->ch, constant("CURLINFO_$item"));
        }
        return $info;
    }

    public static function makeURL($URL, array $parms = [])
    {
        if (empty ($parms)) {
            return $URL;
        }
        $query = http_build_query($parms);
        if ($query && strpos($URL, '?')) {
            $URL .= '&' . $query;
        } else {
            $URL .= '?' . $query;
        }
        return $URL;
    }

    /**
     * Write cURL log, overwrite ::init and ::log to change output target
     * @param $content
     */
    protected function log($content)
    {
        file_put_contents('/tmp/cURL.log', $content . "\n", FILE_APPEND);
    }

}
