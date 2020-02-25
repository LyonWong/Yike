<?php


namespace _;


class serv_
{
    /**
     * 客户端用户标记，长期有效，用于跟踪统计
     */
    const COOKIE_CLIENT_USER = 'CU';
    /**
     * 客户端会话标记，每次访问更新
     */
    const COOKIE_CLIENT_SESS = 'CS';

    protected $platform;

    public function __construct($platform)
    {
        $this->platform = $platform;
    }

    //同步帐号到TIM
    protected function account2Tim($uid, $name, $avatar = '')
    {
        $tim = servTIM::sole($this->platform, servTIM::adminAccount())->tim();
        return $tim->account_import(servUser::sole($this->platform)->uid2usn($uid), $name, $avatar);
    }

    public function uniqueSN($type)
    {
        return uniqid($type);
    }

    public function makeTag($tag, array $args = [])
    {
        $args['_platform_'] = $this->platform;
        ksort($args);
        $key = "$tag:" . http_build_query($args);
        return $key;
    }

    public static function _cache($key, $val=null, $ttl=SECONDS_HOUR)
    {
        $rds = data::redis('cache');
        if ($val) {
            return $rds->set($key, $val, $ttl);
        } else {
            return $rds->get($key);
        }
    }
}