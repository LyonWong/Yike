<?php


namespace Admin\user;


use _\servMoney;
use _\servOrigin;
use Admin\servUser;

class ctrlDetail extends ctrl_
{
    protected $scopeKey = 'user-list';

    public function _DO_()
    {
        $usn = $this->apiGET('usn', '');
        $uid = $this->apiGET('uid', \_\servUser::sole($this->platform)->usn2uid($usn));
        $detail = servUser::sole($this->platform)->detail($uid);
        $tier = servOrigin::sole($this->platform)->tier($detail['origin_id']);
        unset($tier[0]);
        $originName = implode('-', array_column($tier, 'name'));
        $detail['originName'] = $originName;
        $balance = servMoney::sole($this->platform)->balance($uid);
        $detail['balance'] = $balance;
//        $this->apiSuccess($detail);
        $profile = servUser::sole($this->platform)->uid2profile($uid);
        \view::tpl('page', [
            'page' => 'user/detail',
        ])
            ->with('detail', $detail)
            ->with('profile', $profile);
    }

    public function _POST_sendMsg()
    {
        $uid = $this->apiPOST('uid');
        $first = $this->apiPOST('first');
        $type = $this->apiPOST('type');
        $time = $this->apiPOST('time');
        $remark = $this->apiPOST('remark');

        $ret = \_\servMpMsg::sole($this->platform)->toUserNotice($uid, $first, $type, $time, $remark);
        if ($ret) {
            $this->apiSuccess($ret);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }


}