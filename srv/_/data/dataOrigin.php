<?php


namespace _;


use Core\unitInstance;

class dataOrigin extends dataSole
{
    use unitInstance;

    const TABLE = 'dict_origin';

    const DELIMITER = '-';

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
        $this->TABLE = self::TABLE;
    }

    /**
     * 新记录检入
     * @param array $frags
     * @param array $names
     * @return bool|\clsPDOStatement|int|mixed|null
     */
    public function checkIn(array $frags, array $names = [])
    {
        $id = $_id = 0;
        foreach ($frags as $i => $key) {
            $data = [
                'key' => $key,
                '_id' => $_id,
                'depth' => $i + 1,
            ];
            $id = $this->fetchOne($data, 'id', 0);
            if (!$id && $key) { // key非空且未记录时，入库
                $data['name'] = $names[$i] ?? $key;
                $this->insert($data);
                $id = $this->mysql->lastInsertId();
                $ids = $this->seekIds($id);
                $this->update(['ids' => $ids], ['id' => $id]);
            }
            $_id = $id;
        }
        return $id;
    }

    /**
     * @param $id
     * @return string
     */
    public function seekIds($id)
    {
        $ids = [];
        do {
            $ids[] = $id;
        } while ($id = $this->fetchOne(['id'=>$id], '_id', 0));
        return implode(self::DELIMITER, array_reverse($ids));
    }

    /**
     * @param $id
     * @return array
     */
    public function fetchIDs($id)
    {
        $ids = $this->fetchOne(['id' => $id], 'ids', 0);
        return explode(self::DELIMITER, $ids);
    }

    public function fetchByIds($ids)
    {
        $IDs = explode(self::DELIMITER, $ids);
        $res = $this->mysql
            ->s("select * from $this->TABLE")
            ->v("where id in ", $IDs)
            ->a("order by depth")
            ->e()->fetchAll();
        return $res;
    }

    public function fetchIdByKeys($keys)
    {
        $KEYs = explode(self::DELIMITER, $keys);
        $res = $this->mysql
            ->s("select * from $this->TABLE")
            ->v("where `key` in ", $KEYs)
            ->a("order by depth")
            ->e()->fetchAll();
        $id = 0;
        foreach ($res as $item) {
            if ($item['_id'] == $id && $KEYs[$item['depth']-1]==$item['key']) {
                $id = $item['id'];
            }
        }
        return $id;
    }

}