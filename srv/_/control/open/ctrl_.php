<?php


namespace _\open;


use _\dataUserAuth;
use _\servUser;
use _\sign\serv;
use Core\unitAPI;
use Core\unitDoAction;

class ctrl_
{
    use unitDoAction;
    use unitAPI;

    const ERR_ILLEGAL_SIGNATRUE = ['0.1', "Illegal signature"];
    const ERR_ILLEGAL_TIMESTAMP = ['0.2', "Illegal timestamp `%s`"];

    protected $platform;

    protected $channel;

    protected $uid;
    protected $usn;

    public function runBefore()
    {
        $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? null;
        $params = $this->apiRequest(null);
        $this->channel = $this->apiRequest('channel');
        $timestamp = $this->apiRequest('timestamp');
        if (abs($timestamp - time()) > SECONDS_MINUTE*5) {
            $this->apiFailure(self::ERR_ILLEGAL_TIMESTAMP, [$timestamp]);
        }
        $res = serv::sole($this->platform)->check(dataUserAuth::TYPE_OPEN, $this->channel);
        if ($this->signature($params, $res['code']) == $signature) {
            $this->uid = $res['uid'];
            $this->usn = servUser::sole($this->platform)->uid2usn($this->uid);
        } else {
            $this->apiFailure(self::ERR_ILLEGAL_SIGNATRUE);
        }
    }

    protected function signature($data, $secret)
    {
        ksort($data);
        $raw = http_build_query($data)."|$secret";
        $sign = sha1($raw);
        return $sign;
    }

}