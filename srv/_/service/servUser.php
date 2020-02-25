<?php


namespace _;


use Admin\unitQueryUser;
use Core\library\Mysql;
use Core\library\Tool;
use Core\unitInstance;

class servUser extends serv_
{
    use unitInstance;

    const ROLE_MAP = [
        0 => null,
        dataUser::ROLE_ADMIN => 'admin',
        dataUser::ROLE_TEACHER => 'teacher',
        dataUser::ROLE_STUDENT => 'student',
    ];

    const SUBSCRIBLE_MAP = [
        '' => '未关注',
        1 => '已关注',
        0 => '已取关',
    ];

    const SUBSCRIBE_SCENE_MAP = [
        '' => '已关注',
        'ADD_SCENE_SEARCH' => '√公众号搜索',
        'ADD_SCENE_ACCOUNT_MIGRATION' => '√公众号迁移',
        'ADD_SCENE_PROFILE_CARD' => '√名片分享',
        'ADD_SCENE_QR_CODE' => '√扫描二维码',
        'ADD_SCENE_PROFILE_LINK' => '√图文页内名称点击',
        'ADD_SCENE_PROFILE_ITEM' => '√图文页右上角菜单',
        'ADD_SCENE_PAID' => '√支付后关注',
        'ADD_SCENE_OTHERS' => '√其他'
    ];

    const SETTING_MAP = [
        dataUser::NOTICE_PRECLASS => '课前提醒',
        dataUser::NOTICE_ABSENCE => '未听课提醒',
        dataUser::NOTICE_COMMISSION => '佣金提醒',
        dataUser::NOTICE_PAYOFF => '结算提醒',
        dataUser::NOTICE_BOARD => '留言通知',
        dataUser::AUTO_REFUND => '自动退款',
    ];

    const SETTING_DEFAULT = [
        dataUser::AUTO_REFUND => 1,
        dataUser::NOTICE_PAYOFF => 1,
        dataUser::NOTICE_PRECLASS => 1,
        dataUser::NOTICE_ABSENCE => 1,
        dataUser::NOTICE_COMMISSION => 1,
        dataUser::NOTICE_BOARD => 1
    ];


    protected $uid;
    protected $usn;
    protected $data;

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform = null)
    {
        parent::__construct($platform);
        $this->data = dataUser::sole($this->platform);
    }

    public function usn2uid($usn)
    {
        return $this->data->fetchOne(['sn' => $usn], 'id', 'id');
    }

    public function uid2usn($uid)
    {
        return $this->data->fetchOne(['id' => $uid], 'sn', 'sn');
    }

    public function usn2profile($usn)
    {
        $res = $this->data->fetchOne(['sn' => $usn], ['name', 'tms_update']);
        if ($res) {
            $profile = [
                'sn' => $usn,
                'name' => $res['name'],
                'avatar' => \view::upload("user/$usn/avatar!avatar", Tool::timeEncode($res['tms_update']))
            ];
        } else {
            $profile = [];
        }
        return $profile;
    }

    public function uid2profile($uid)
    {
        $res = $this->data->fetchOne(['id' => $uid], ['sn', 'name', 'tms_update']);
        $profile = [
            'sn' => $res['sn'],
            'name' => $res['name'],
            'avatar' => \view::upload("user/$res[sn]/avatar-avatar", Tool::timeEncode($res['tms_update']))
        ];
        return $profile;
    }

    public function uids2profile(array $uids)
    {
        if (count($uids) == 0) {
            return [];
        }
        $raw = $this->data->fetchByUids($uids, ['id', 'sn', 'name', 'tms_update'], 'id');
        $ret = [];
        foreach ($uids as $uid) {
            $ret[$uid] = [
                'sn' => $raw[$uid]['sn'],
                'name' => $raw[$uid]['name'],
                'avatar' => \view::upload("user/{$raw[$uid]['sn']}/avatar!avatar", Tool::timeEncode($raw[$uid]['tms_update']))
            ];
        }
        return $ret;
    }

    public function uid2origin($uid)
    {
        $originId = $this->data->fetchOne(['id' => $uid], 'origin_id', 0);
        // todo map to origin key
        return $originId;
    }

    public function uid2info($uid, $fields)
    {
        $fields = explode(',', $fields);
        if ($fields != array(0 => '')) {
            foreach ($fields as $k => $v) {
                $fields[$k] = 'JSON_UNQUOTE(info->' . "'$.$v') as $v";
            }
            $fields = implode(',', $fields);
            return $this->data->fetchOne(['id' => $uid], $fields);

        } else {
            $ret = $this->data->fetchOne(['id' => $uid], 'info', 'info');
            $ret = json_decode($ret, true);
            unset($ret['openid'], $ret['unionid']);
            return $ret;
        }
    }

    public function usn2openid($usn)
    {
        return $this->data->fetchOne(['sn' => $usn], "JSON_UNQUOTE(info->'$.openid')", 0);
    }

    public function uid2setting($uid, $field = null)
    {
        if ($field) {
            $res = $this->data->fetchOne(['id' => $uid], 'JSON_UNQUOTE(setting->' . "'$.$field')", 0);
            if ($res === null) {
                return self::SETTING_DEFAULT[$field] ?? null;
            } else {
                return $res;
            }
        } else {
            $ret = $this->data->fetchOne(['id' => $uid], 'setting', 'setting');
            return json_decode($ret, true);
        }
    }

    public function openid2uid($openId)
    {
        return $this->data->searchByOpenId($openId);
    }

    public function checkRole($uid, $iRole): bool
    {
        return (bool)$this->data->fetchOne(['id' => $uid], "i_role & $iRole", 0);
    }


    public function get($name)
    {
        return $this->$$name;
    }

    public function getList($iRole)
    {
        $res = $this->data->fetchAll(['i_role' => $iRole], '*');
        return $res;
    }

    public function page(unitQueryUser $query, $pageNum, $pageStep)
    {
        if ($query->userValue) {
            $where = [];
        } else {
            $where = [
                'tms_create between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"]
            ];
        }

        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['id'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['sn'] = $query->userValue;
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["id in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->subscrible) {
            $key = array_search($query->subscrible, self::SUBSCRIBLE_MAP);
            if ($key === '') {
                $where[] = "info->'$.subscribe' is null";
            } else if ($key !== false) {
                $where["JSON_CONTAINS(info,?,'$.subscribe')"] = [$key];
            }
        }


        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

    public function detail($uid)
    {
        $user = dataUser::sole($this->platform)->fetchOne(['id' => $uid], ['id', 'sn', 'name', 'origin_id', 'info', 'setting', 'tms_create', 'tms_update']);
        $user['info'] = json_decode($user['info'], true);
        $user['setting'] = json_decode($user['setting'], true);

        return $user;
    }

    public function updateName($uid, $name)
    {
        $user = dataUser::sole($this->platform)->fetchOne(['id' => $uid], ['name', 'info']);
        $user['info'] = json_decode($user['info'], true);
        $user['info']['name'] = $name;
        $user['info'] = json_encode($user['info']);
        $user['name'] = $name;

        //同步帐号到TIM
        $this->account2Tim($uid, $name);

        return dataUser::sole($this->platform)->update($user, ['id' => $uid])->rowCount();

    }

    public function uploadAvatar($uid, $tmpName)
    {
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        dataUser::sole($this->platform)->update(['tms_update' => date('Y-m-d H:i:s')], ['id' => $uid])->rowCount();
        return servQiniu::inst()->putFile('user/' . $usn . '/avatar', $tmpName);
    }

    public function userSetByopenId($openId, $item, $value)
    {
        $uid = $this->openid2uid($openId);
        $this->userSet($uid, $item, $value);
        return $value ? '您已成功开启' . self::SETTING_MAP[$item] . '，回复「关闭' . self::SETTING_MAP[$item] . '」可关闭' : '您已成功关闭' . self::SETTING_MAP[$item] . ',回复「开启' . self::SETTING_MAP[$item] . '」可开启';

    }

    public function userSet($uid, $item, $value)
    {
        $setting = dataUser::sole($this->platform)->fetchOne(['id' => $uid], 'setting', 'setting');
        $setting = json_decode($setting, true);
        switch ($item) {
            case dataUser::NOTICE_ABSENCE:

                $setting['notice'][substr(strstr(dataUser::NOTICE_ABSENCE, '.', false), 1)] = $value;
                break;
            case dataUser::NOTICE_COMMISSION:
                $setting['notice'][substr(strstr(dataUser::NOTICE_COMMISSION, '.', false), 1)] = $value;
                break;
            case dataUser::NOTICE_PAYOFF:
                $setting['notice'][substr(strstr(dataUser::NOTICE_PAYOFF, '.', false), 1)] = $value;
                break;
            case dataUser::NOTICE_PRECLASS:
                $setting['notice'][substr(strstr(dataUser::NOTICE_PRECLASS, '.', false), 1)] = $value;
                break;
            case dataUser::NOTICE_BOARD:
                $setting['notice'][substr(strstr(dataUser::NOTICE_BOARD, '.', false), 1)] = $value;
                break;
            case dataUser::AUTO_REFUND:
                $setting[dataUser::AUTO_REFUND] = $value;
                break;
            default:
                break;
        }

        return dataUser::sole($this->platform)->update(['setting' => json_encode($setting)], ['id' => $uid])->rowCount();

    }
}