<?php


namespace _;

use Core\unitInstance;


class dataUserAuth extends dataSole
{
    use unitInstance;

    const TABLE = 'user_auth';

    const TYPE_EMAIL = 1;
    const TYPE_WEIXIN = 2; // 微信公众号
    const TYPE_WXA = 3; // 微信小程序
    const TYPE_ZSXQ = 4; // 知识星球
    const TYPE_TELEPHONE = 5; // 手机号
    const TYPE_OPEN = 6; // OpenAPI

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($iType, $uid, $uaid, $code, $expire = 0, array $extra = [])
    {
        $try = 10;
        do {
            $this->mysql->insert(self::TABLE, [
                'i_type' => $iType,
                'uid' => $uid,
                'uaid' => $uaid,
                'code' => $code,
                'expire' => $expire,
                'extra' => json_encode($extra, JSON_FORCE_OBJECT)
            ]);
            $id = self::mysql()->lastInsertId();
            if (--$try < 0) {
                return false;
            }
        }while (!$id);

        return $id;
    }

    public function search($iType, $uaid)
    {
        $res = $this->mysql->select(self::TABLE, ['uid', 'code', 'extra'], [
            'i_type' => $iType,
            'uaid' => $uaid,
        ])->fetch();
        return $res;
    }

    public function fetchCodeByUid($iType, $uid)
    {
        return $this->fetchOne(['i_type' => $iType, 'uid' => $uid ], ['code'], 0);
    }

    public function updateByUaid($iType, $uaid, $field, $value): bool
    {
        $res = self::mysql()->update(self::TABLE, [$field => $value], ['i_type' => $iType, 'uaid' => $uaid])->rowCount();
        return (bool)$res;
    }
}
