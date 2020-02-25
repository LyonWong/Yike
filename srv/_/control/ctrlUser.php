<?php


namespace _;


use Core\library\Tool;
use _\sign\serv;

class ctrlUser extends ctrlSess
{
    const ERR_NO_SUCH_USER = ['1', 'No such user'];

    public function _DO_profile()
    {
        $usn = $this->apiRequest('usn', $this->usn);
        $res = servCache::sole($this->platform)->usn2base($usn);
        if (!$res) {
            $this->apiFailure(self::ERR_NO_SUCH_USER);
        }
        $res['sn'] = $usn;
        $res['avatar'] = \view::upload("user/$usn/avatar-avatar", Tool::timeEncode($res['tms_update']));
        unset($res['id']);
        $this->apiSuccess($res);
    }

    public function _POST_info($act)
    {
        switch ($act) {
            case 'update':
                $userInfo = $this->apiRequest(null);
                $res = sign\serv::sole($this->platform)->save($this->uid, $userInfo);
                $this->apiSuccess(['update'=> $res]);
                break;
            case 'refresh':
                $daoUserAuth = dataUserAuth::sole($this->platform);
                $srvWeixin = weixin\serv::sole($this->platform);
                if ($code = $daoUserAuth->fetchCodeByUid(dataUserAuth::TYPE_WEIXIN, $this->uid)) {
                    $userInfo = $srvWeixin->info($code, 'mp');
                } else if ($code = $daoUserAuth->fetchCodeByUid(dataUserAuth::TYPE_WXA, $this->uid)) {
                    $userInfo = $srvWeixin->info($code, 'wxa');
                }
                if (isset($userInfo)) {
                    serv::sole($this->platform)->save($this->uid, $userInfo);
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ['Failed to fetch user info']);
                }
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, ['Invalid refresh item']);
        }
        servCache::sole($this->platform)->del(servCache::TAG_USER_BASE.$this->usn);
        $this->_DO_profile();
    }

}