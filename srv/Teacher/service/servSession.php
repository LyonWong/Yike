<?php


namespace Teacher;


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

    public static $menuKey;


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
        $res = dataUser::sole($platform)->fetchOne(['sn' => $usn], ['id', 'name']);
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

    public function check($flag, $token)
    {
        $_token = data::redis()->hGet($this->key, "F_$flag");
        return $token === $_token;
    }

    public function close()
    {
        return data::redis()->del($this->key);
    }

}
