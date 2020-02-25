<?php
/**
 * originId = 0 表示根节点
 * originKey = _ 表示自然量
 */


namespace _;


use Core\unitInstance;

class servOrigin extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function profile($id)
    {
        $res = dataOrigin::sole($this->platform)->fetchOne([
                'id' => $id
            ], ['id', 'ids', 'key', 'name']) ?: [
                'id' => 0,
                'ids' => 0,
                'key' => '*',
                'name' => '全部'
            ];

        return $res;
    }

    /**
     * @param string $origin
     * @return int
     */
    public function key2id($origin):int
    {
        //todo add cache
        $id = dataOrigin::sole($this->platform)->fetchIdByKeys($origin);
        return intval($id);
    }

    public function id2key($id):string
    {
        $dataOrigin = dataOrigin::sole($this->platform);
        $ids = $dataOrigin->fetchOne(['id' => $id], ['ids'], 0);
        $list = $dataOrigin->fetchByIds($ids);
        $keys = array_column($list, 'key');
        return implode(dataOrigin::DELIMITER, $keys);
    }

    /**
     * 将来源KEY入库，转化为ID
     * @param $originKey
     * @param null $originName
     * @return int
     */
    public function checkIn($originKey, $originName = null)
    {
        $frags = explode(dataOrigin::DELIMITER, $originKey);
        if ($originName) {
            $names = explode(dataOrigin::DELIMITER, $originName);
        } else {
            $names = [];
        }
        $id = dataOrigin::sole($this->platform)->checkIn($frags, $names);
        return intval($id);
    }

    /**
     * 按KEY前缀查询
     * @param $prefix
     * @return array
     */
    public function keySearch($prefix)
    {
        $res = dataOrigin::sole($this->platform)->fetchAll(['key like ?' => $prefix . '%'], '*', 'key');
        return $res;
    }

    public function trim($which, $depth)
    {
        $ids = dataOrigin::sole($this->platform)->fetchOne($which, 'ids', 0);
        $IDs = explode(dataOrigin::DELIMITER, $ids);
        return $IDs[$depth] ?? 0;
    }

    public function chop($which, $depth)
    {
        $ids = dataOrigin::sole($this->platform)->fetchOne($which, 'ids', 0);
        $res = dataOrigin::sole($this->platform)->fetchByIds($ids);
        return array_slice($res, 0, $depth);
    }

    public function next($which, $preId)
    {
        $ids = dataOrigin::sole($this->platform)->fetchOne($which, 'ids', 0);
        $IDs = explode(dataOrigin::DELIMITER, $ids);
        if ($preId) {
            $i = array_search($preId, $IDs);
            if ($i === false) {
                return false;
            } else {
                return $IDs[$i+1] ?? false;
            }
        } else {
            return $IDs[0] ?? 0;
        }
    }

    public function tier($id)
    {

        $tier = [
            [
                'id' => 0,
                'key' => '*',
                'name' => '※',
                'depth' => 0,
            ]
        ];
        $dataOrigin = dataOrigin::sole($this->platform);
        $ids = $dataOrigin->fetchOne(['id' => $id], ['ids'], 0);
        $list = $dataOrigin->fetchByIds($ids);
        return array_merge($tier, $list);
    }

    public function listSubs($oid)
    {
        $ids = dataOrigin::sole($this->platform)->fetchOne(['id' => $oid], 'ids', 0);
        $pattern = $ids ? "$ids-%" : '%';
        $res = dataOrigin::sole($this->platform)->fetchAll(
            [
                'ids like ?' => [$pattern],
                '_id' => $oid
            ],
            '*'
        );
        return $res;
    }

    public function findPre($oid)
    {
        $_id = dataOrigin::sole($this->platform)->fetchOne(['id'=>$oid], '_id', 0);
        $res = dataOrigin::sole($this->platform)->fetchOne(['id'=>$_id], '*');
        return $res;
    }

    public function name($oid, $value)
    {
        return dataOrigin::sole($this->platform)->update([
            'name' => $value
        ], ['id' => $oid]);
    }

    public function cache($sn, $uid, $originId=null)
    {
        $key = "ORIGIN_CACHE_$sn-$uid";
        $redis = data::redis();
        if ($originId) {
            $redis->setex($key, SECONDS_DAY, $originId);
        }
        return $redis->get($key);
    }

}