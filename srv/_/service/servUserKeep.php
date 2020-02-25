<?php


namespace _;


use Core\unitInstance;

class servUserKeep extends serv_
{
    use unitInstance;

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }

    public function getBalance($uid)
    {
        return dataUserKeep::sole($this->platform)->fetchOne(['uid' => $uid], 'JSON_UNQUOTE(obj->' . "'$.balance') as balance",'balance');
    }

    public function setBalance($uid, $val)
    {}

    public function varBalance($uid, $val)
    {

    }

}