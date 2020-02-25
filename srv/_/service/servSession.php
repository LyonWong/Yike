<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class servSession extends serv_
{
    use unitInstance;

    const EXPIRE = SECONDS_DAY * 30;

    protected $uid;

    protected $usn;

    protected $name;

    protected $key;

    public static $lang;

    public static $scopeKey;


    /**
     * @var self
     */
    public static $live;

    /**
     * @param $platform
     * @param $usn
     * @return servSession
     */
    public static function sole($platform, $usn)
    {
        $inst = self::_singleton($platform, $usn);
        $inst->usn = $usn;
//        $res = dataUser::sole($platform)->fetchOne(['sn' => $usn], ['id', 'name']);
        $res = servCache::sole($platform)->usn2base($usn);
        $inst->uid = $res['id'];
        $inst->name = $res['name'];
        $inst->key = "YK_$inst->usn";
        self::$live = $inst;
        return $inst;
    }

    public function __construct($platform = null, $usn)
    {
        parent::__construct($platform);
    }

    public function get($name)
    {
        return $this->$name;
    }

    public function start($flag)
    {
        $token = uniqid($this->uid, true);
        data::redis()->hSet($this->key, "F_$flag", $token);
        data::redis()->expire($this->key, self::EXPIRE);
        return "$this->usn-$token";
    }

    public function cipher($code)
    {
        $cipher = Tool::genSecret(32, Tool::STR_FORMAL);
        data::redis()->set('SIGN_CIPHER_'.$cipher, "$this->usn|$code", SECONDS_MINUTE*5);
        return $cipher;
    }

    public static function verify($cipher, $code)
    {
        $rkey = "SIGN_CIPHER_$cipher";
        if ($info = data::redis()->get($rkey)) {
            data::redis()->del($rkey);
            list($usn, $_code) = explode('|', $info);
            return  ($code == $_code) ? $usn : false;
        } else {
            return false;
        }
    }

    public function check($flag, $token)
    {
        $_token = data::redis()->hGet($this->key, "F_$flag");
        return $token === $_token;
    }

    public function check2uid($flag, $token)
    {
        if ($this->check($flag, $token)) {
            return $this->uid;
        } else {
            return false;
        }
    }

    public function close()
    {
        return data::redis()->del($this->key);
    }

}
