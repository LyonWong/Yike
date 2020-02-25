<?php


namespace _;


use Core\unitInstance;

class servCache extends serv_
{
    use unitInstance;

    const TAG_USER_BASE = 'USER_BASE_';
    const TAG_LESSON_AUTH = 'LESSON_AUTH_';
    const TAG_LESSON_SLICE = 'LESSON_SLICE_';
    const TAG_LESSON_LIST = 'LESSON_LIST_';
    const TAG_LESSON_PROFILE = 'LESSON_PROFILE_';
    const TAG_LESSON_DETAIL = 'LESSON_DETAIL_';
    const TAG_LESSON_EXTRA = 'LESSON_EXTRA_';
    const TAG_LESSON_RATE_LIST = 'LESSON_RATE_LIST_';
    const TAG_LESSON_RATE_COUNT = 'LESSON_RATE_COUNT_';
    const TAG_LESSON_RANK_SLICE = 'LESSON_RANK_SLICE_';
    const TAG_LESSON_HOME = 'LESSON_HOME_';

    private $rds;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->rds = data::redis('cache');
    }

    public function set($key, $val, $ttl)
    {
        return $this->rds->set($key, $val, $ttl);
    }

    public function get($key)
    {
        return $this->rds->get($key);
    }

    public function del($key)
    {
        return $this->rds->del($key);
    }

    public function setJson($key, $val, $ttl)
    {
        return $this->set($key, json_encode($val), $ttl);
    }

    public function getJson($key)
    {
        return json_decode($this->get($key), true);
    }

    public function usn2base($usn)
    {
        $key = self::TAG_USER_BASE.$usn;
        $res = $this->getJson($key);
        if (!$res) {
            $res = dataUser::sole($this->platform)->fetchOne(['sn'=>$usn], ['id', 'name', 'tms_update']);
            $this->setJson($key, $res, SECONDS_HOUR);
        }
        return $res;
    }

}