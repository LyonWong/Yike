<?php


namespace _;

use Core\unitInstance;

class servDWZ extends serv_
{
    use unitInstance;
    protected $apiUrl = 'http://suo.im/api.php?format=json';

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
    }

    public function convert2ShortUrl($url)
    {
        $url = $this->apiUrl . '&url=' . urlencode($url);
        $ret = $this->https_request($url);
        $ret = json_decode($ret,true);
        return $ret;
    }

    // https请求（支持GET和POST）
    protected  function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty ($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}