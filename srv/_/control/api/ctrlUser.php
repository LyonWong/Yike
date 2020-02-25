<?php


namespace _\api;


use _\dataUserAuth;
use _\servCache;
use _\servLessonHub;
use _\servMoney;
use _\servPayoff;
use _\servTeacher;
use _\servUser;
use _\sign\serv;
use _\unitTeacherDatum;
use Core\library\Tool;

class ctrlUser extends ctrlSigned
{
    const ERR_ILLEGAL_USER = ['1', 'Illegal user `%s`'];
    const ERR_TEST_FAILED = ['2', 'Failed to pass `%s` test'];

    public function _DO_profile()
    {
        $usn = $this->apiGET('usn', $this->usn);
        $res = servUser::sole($this->platform)->usn2profile($usn);
        if (count($res)) {
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_ILLEGAL_USER, [$usn]);
        }
    }

    public function _GET_privacy()
    {
        $info = servUser::sole($this->platform)->uid2info($this->uid, 'subscribe,telephone');
        $data = [
            'telephone' => $info['telephone'] ?? null,
            'subscribe' => boolval($info['subscribe']),
        ];
        $this->apiSuccess($data);
    }

    public function _POST_info($act)
    {
        switch ($act) {
            case 'update':
                $userInfo = $this->apiRequest(null);
                $res = serv::sole($this->platform)->save($this->uid, $userInfo);
                $this->apiSuccess(['update' => $res]);
                break;
            case 'refresh':
                $daoUserAuth = dataUserAuth::sole($this->platform);
                $srvWeixin = \_\weixin\serv::sole($this->platform);
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
        servCache::sole($this->platform)->del(servCache::TAG_USER_BASE . $this->usn);
        $this->_DO_profile();
    }

    /**
     * 检查用户资格
     * @param $sn
     */
    public function _DO_test($sn)
    {
        $items = $this->apiGET('items', []);
        foreach ($items as $item) {
            switch ($item) {
                case 'access':
                    $res = servLessonHub::sole($this->platform)->check($this->uid, $sn, 'access');
                    if ($res < 1) {
                        $this->apiFailure(self::ERR_TEST_FAILED, [$item], [
                            'info' => '请先报名所有课程',
                        ]);
                    }
                    break;
                case 'confirm':
                    $res = servLessonHub::sole($this->platform)->check($this->uid, $sn, 'confirm');
                    if ($res < 1) {
                        $this->apiFailure(self::ERR_TEST_FAILED, [$item], [
                            'info' => '请先确认所有课程',
                        ]);
                    }
                    break;
                case 'bind-telephone':
                    $res = dataUserAuth::sole($this->platform)->fetchCodeByUid(dataUserAuth::TYPE_TELEPHONE, $this->uid);
                    if (!$res) {
                        $this->apiFailure(self::ERR_TEST_FAILED, [$item], [
                            'info' => '请先绑定手机号',
                            'goto' => '/user/bind/telephone'
                        ]);
                    }
                    break;
                default:
                    $this->apiFailure(self::ERR_TEST_FAILED, [$item], "unknown item");
            }
        }
        $this->apiSuccess();
    }

    public function _DO_teacher()
    {
        $datum = servTeacher::sole($this->platform)->datum($this->uid);
        $this->apiSuccess($datum);
    }

    public function _POST_apply($opt)
    {
        switch ($opt) {
            case 'teacher':
                $datum = new unitTeacherDatum();
                $datum->about = $this->apiPOST('about', '');
                $id = servTeacher::sole($this->platform)->apply($this->uid, $datum);
                if ($id) {
                    $this->apiSuccess();
                }
                break;
        }
        $this->apiFailure(self::ERR_UNDEFINED, ['Unknown']);
    }

    public function _POST_update($item)
    {
        switch ($item) {
            case 'avatar':
                servUser::sole($this->platform)->uploadAvatar($this->uid, $_FILES['avatar']['tmp_name']);
                break;
            case 'name':
                $name = $this->apiPOST('name');
                servUser::sole($this->platform)->updateName($this->uid, $name);
                break;
        }
        $this->_DO_profile();
    }

    public function _POST_teacher($opt)
    {
        $datum = new unitTeacherDatum();
        $datum->about = $this->apiPOST('about', '');
        switch ($opt) {
            case 'apply':
                $res = servTeacher::sole($this->platform)->apply($this->uid, $datum);
                if ($res) {
                    $this->apiSuccess();
                }
                break;
            case 'update':
                $res = servTeacher::sole($this->platform)->update($this->uid, $datum);
                $this->apiSuccess($res);
                break;
        }
        $this->apiFailure(self::ERR_UNDEFINED, ['Unknown']);
    }

    public function _GET_money($opt)
    {
        $srv = servMoney::sole($this->platform);
        switch ($opt) {
            case 'overview':
                $res = $srv->overview($this->uid);
                $this->apiSuccess($res);
                break;
            case 'detail':
                $cursor = $this->apiGET('cursor', Tool::timeEncode('now'));
                $limit = $this->apiGET('limit', 10);
                $res = $srv->debit($this->uid, $cursor, $limit);
                $this->apiSuccess($res);
                break;
            case 'orders':
                $cursor = $this->apiGET('cursor', 0);
                $limit = $this->apiGET('limit', 10);
                $res = $srv->orders($this->uid, $cursor, $limit);
                $this->apiSuccess($res);
                break;
        }
    }

    public function _POST_money($opt)
    {
        switch ($opt) {
            case 'drawcash':
                $amount = $this->apiPOST('amount')*100;
                if (round($amount) != $amount) {
                    $this->apiFailure(self::ERR_UNDEFINED, ['Illegal amount']);
                }
                $srvPayoff = servPayoff::sole($this->platform);
                $check = $srvPayoff->checkDrawcash($this->uid, $amount, 'today');
                if ($check->error) {
                    $this->apiFailure(self::ERR_UNDEFINED, [$check->message]);
                } else {
                    $result = $srvPayoff->drawcash($this->uid, $amount);
                    if ($result->error == 0) {
                        $overview = $srvPayoff->overview($this->uid);
                        $this->apiSuccess($overview);
                    } elseif ($result->error == 2) {
                        $this->apiFailure(self::ERR_UNDEFINED, ['提现繁忙，请联系管理员']);
                    } else {
                        $this->apiFailure(self::ERR_UNDEFINED, [$result->message]);
                    }
                }
                break;
        }
    }

}