<?php


namespace Admin;


class dataScopeGroup extends data_
{
    const TABLE = 'scope_group';


    public static function fetchAuths(...$groupIds):array
    {
        if (count($groupIds) == 0) {
            return [];
        }
        $list = self::mysql()->s("SELECT `auths` FROM " . self::TABLE)->v("WHERE `id` in", $groupIds)->e()->fetchAll(null, 0);
        foreach ($list as &$item) {
            $item = json_decode($item, true);
        }
        return $list;
    }
    
    public static function fetchInfo($groupId)
    {
        $res = self::mysql()->select(self::TABLE, '*', ['id'=>$groupId])->fetch();
        $res['auths'] = json_decode($res['auths'], true);
        return $res;
    }
    
    public static function insert($plat, $name)
    {
        $data = [
            'plat' => $plat,
            'name' => $name,
            'auths' => '{}',
        ];
        self::mysql()->insert(self::TABLE, $data);
        return self::mysql()->lastInsertId();
    }

    public static function setAuth($groupId, $field, $point, $priv)
    {
        $preAuth = self::mysql()->select(self::TABLE, 'auths', ['id' => $groupId])->fetch(0);
        $newAuth = arrayMergeForce(json_decode($preAuth, true), [
            $field => [
                $point => $priv
            ]
        ]);
        $auth = json_encode($newAuth, JSON_FORCE_OBJECT);
        return self::update($groupId, ['auths' => $auth]);
    }


}