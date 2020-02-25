<?php

namespace Student;


use _\servCache;
use _\servMpMsg;
use _\servQiniu;
use _\config;
use Core\library\Tool;


class ctrlUser extends ctrlSess
{
    const ERR_UPDATE_NAME_FAILED = ['1', 'update name failed'];

    public function _DO_profile()
    {
        $usn = $this->apiRequest('usn', $this->usn);
        $res = servCache::sole($this->platform)->usn2base($usn);
        $res['sn'] = $usn;
        $res['avatar'] = \view::upload("user/$usn/avatar-avatar", Tool::timeEncode($res['tms_update']));
        unset($res['id']);
        $this->apiSuccess($res);
    }

    public function _GET_avatar_draft()
    {
        $key = uniqid('draft/avatar/');
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
        ];
        $this->apiSuccess($data);
    }

    public function _POST_update($type)
    {
        switch ($type) {
            case 'name':
                $name = $this->apiPOST('name');
                $ret = servUser::sole($this->platform)->updateName($this->uid, $name);
                if ($ret) {
                    $this->apiSuccess();
                }
                $this->apiFailure(self::ERR_UPDATE_NAME_FAILED);
                break;

            case 'avatar':
                $avatar = $this->apiPOST('avatar');
                //七牛图片设置为永久有效
                servQiniu::inst()->deleteAfterDays($avatar, 0);
                servQiniu::inst()->move($avatar, 'user/' . $this->usn . '/avatar');
                dataUser::sole($this->platform)->update(['tms_update' => date('Y-m-d H:i:s')], ['id' => $this->uid]);
                $this->apiSuccess();
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED);

        }
    }


}