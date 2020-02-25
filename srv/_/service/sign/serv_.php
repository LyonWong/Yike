<?php


namespace _\sign;


use _\data;
use _\dataUser;
use _\dataUserAuth;
use _\servOrigin;
use _\servQiniu;

class serv_ extends \_\serv_
{
    /**
     * 创建新用户
     * @param $type
     * @param $uaid
     * @param $code
     * @param $name
     * @param $originKey
     * @param array $userInfo
     * @return bool|int
     */
    public function create($type, $uaid, $code, $name, $originKey, $userInfo = [])
    {
        $lock = "LOCK_SIGN_$type:$uaid";
        if (data::redis()->get($lock)) {
            return false;
        } else {
            data::redis()->setex($lock, 10, 1);
        }
        $originId = servOrigin::sole($this->platform)->key2id($originKey) ?: servOrigin::sole($this->platform)->checkIn($originKey);
        $daoUser = dataUser::sole($this->platform);
        $uid = $daoUser->append(0, $name, $originId, json_encode($userInfo));
        dataUserAuth::sole($this->platform)->append($type, $uid, $uaid, $code, 0);
        $this->save($uid, $userInfo);
        data::redis()->del($lock);
        return $uid;
    }

    public function check($type, $uaid)
    {
        return dataUserAuth::sole($this->platform)->search($type, $uaid);
    }

    /**
     * 关联授权
     * @param $type
     * @param $uaid
     * @param $code
     * @param $_type
     * @return int|bool uid
     */
    public function assoc($type, $uaid, $code, $_type)
    {
        $dao = dataUserAuth::sole($this->platform);
        $_auth = $dao->search($_type, $uaid);
        if ($_auth) {
            $dao->append($type, $_auth['uid'], $uaid, $code);
            return $_auth['uid'];
        } else {
            return false;
        }
    }

    /**
     * 保存用户信息，并同步到七牛和TIM
     * @param $uid
     * @param array $userInfo <name, avatar, sex, city, province, country, language, openid, unionid, subscribe>
     * @return bool|mixed
     */
    public function save($uid, array $userInfo)
    {
        $daoUser = dataUser::sole($this->platform);
        $user = $daoUser->fetchOne(['id' => $uid], ['sn', 'name', 'info']);
        $info = array_merge(json_decode($user['info'], true) ?? [], $userInfo);
        $info['name'] = $info['name'] ?? '佚名';
        $info['avatar'] = $info['avatar'] ?? \view::upload('user/default/avatar');
        servQiniu::inst()->fetch($info['avatar'], "user/$user[sn]/avatar");
        $this->account2Tim($uid, $info['name'], $info['avatar']);
        return $daoUser->update([
            'name' => $info['name'],
            'info' => json_encode($info)
        ], ['id' => $uid])->rowCount();
    }


}