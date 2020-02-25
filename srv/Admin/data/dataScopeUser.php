<?php


namespace Admin;

use Core\unitInstance;
use Core\unitMysqlStaticData;

class dataScopeUser extends data_
{
    use unitInstance;
    use unitMysqlStaticData;

    const TABLE = 'scope_user';

    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }

    public static function fetchByUid($uid):array
    {
        $res = self::fetchOne(['uid'=>$uid], ['groups', 'auths']);
        $res['groups'] = isset($res['groups']) ? json_decode($res['groups'], true) : [];
        $res['auths'] = isset($res['auths']) ? json_decode($res['auths'], true) : [];
        return $res;
    }

    public function updateAuth($uid, $field, $point, $priv)
    {
        $auth = self::mysql()->select(self::TABLE, "auths", ['uid' => $uid])->fetch(0);
        $auths = json_decode($auth, true);
        $auths[$field][$point] = $priv;
        $res = self::mysql()->insert(self::TABLE, [
            'uid' => $uid,
            'auths' => json_encode($auths)
        ], ['auths']);
        $t = self::mysql()->select("scope_user", "*")->fetchAll();
    }

}