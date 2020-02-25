<?php


namespace _;


use Core\unitInstance;

class dataUser extends dataSole
{
    use unitInstance;

    const TABLE = 'user';

    const ROLE_ADMIN = 1;
    const ROLE_TEACHER = 2;
    const ROLE_STUDENT = 4;

    //用户提醒开关
    const NOTICE_PRECLASS = 'notice.preclass'; //课前提醒
    const NOTICE_ABSENCE = 'notice.absence'; //未听课提醒
    const NOTICE_COMMISSION = 'notice.commission'; //佣金提醒
    const NOTICE_PAYOFF = 'notice.payoff'; //结算提醒
    const NOTICE_BOARD = 'notice.board';//回复通知
    //自动结算
    const AUTO_REFUND = 'auto_refund';



    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform=null)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($iRole = '0', $name = '', $originId = 0, $info = '{}'): int
    {
        $data = [
            'i_role' => $iRole,
            'name' => $name,
            'origin_id' => $originId,
            'info' => $info,
            'setting' => '{}',
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_USER);
            self::mysql()->insert(self::TABLE, $data);
            $uid = (int)self::mysql()->lastInsertId();
            if (--$try < 0) {
                return false;
            }
        } while (!$uid);
        return $uid;
    }

    public function fetchByUids(array $uids, $field, $index=null, $value=null)
    {
        $semi = $this->mysql::makeData($uids, '?', ',');
        $where = [
            "id in ($semi[clause])" => $semi['params']
        ];
        return $this->fetchAll($where, $field, $index, $value);
    }

    public static function updateByUid($uid, $field, $value): bool
    {
        $res = self::mysql()->update(self::TABLE, [$field=>$value], ['id' => $uid])->rowCount();
        return (bool)$res;
    }

    public static function fetchByUid($uid, $fields, $index=null)
    {
        return self::mysql()->select(self::TABLE, $fields, ['id' => $uid])->fetch($index);
    }

    public function searchByName($name)
    {
        $pattern = str_replace('*', '%', $name);
        return $this->fetchAll([
            'name like ?' => [$pattern]
        ], 'id', null, 0);
    }

    public function searchByOpenId($openId)
    {
        return $this->mysql->a('order by id desc')->run("select  id from $this->TABLE where info->'$.openid' = '$openId'")->fetch(0);
    }
    

}