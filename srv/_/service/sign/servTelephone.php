<?php


namespace _\sign;



use _\dataUser;
use _\dataUserAuth;
use Core\unitInstance;

class servTelephone extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function bind($uid, $telephone)
    {
        $this->save($uid, ['telephone' => $telephone]);
        $daoUserAuth = dataUserAuth::sole($this->platform);
        $code = $daoUserAuth->fetchCodeByUid(dataUserAuth::TYPE_TELEPHONE, $uid);
        if ($code) { // 此前有绑定手机
            $res = $daoUserAuth->update([
                'uaid' => $telephone
            ], [
                'i_type' => dataUserAuth::TYPE_TELEPHONE,
                'uid' => $uid
            ])->rowCount();
        } else { // 新绑定手机
            $res = $daoUserAuth->append(dataUserAuth::TYPE_TELEPHONE, $uid, $telephone, 1);
        }
        return $res;
    }


}