<?php


namespace Student\pay;


use _\servOrigin;
use _\servUnionOrder;
use Student\servUser;

class ctrlGroup extends ctrl_
{
    protected $srvUnion;

    public function __construct()
    {
        parent::__construct();
        $this->srvUnion = servUnionOrder::sole($this->platform);
    }

    public function _POST_create()
    {
        //创建团体订单
        $target = $this->apiPOST('target');
        $notice = $this->apiPOST('notice');
        $originKey = $this->apiPOST('origin', '');

        $srvOrigin = servOrigin::sole($this->platform);
        $srvUser = servUser::sole($this->platform);

        if ($originKey) { // 存在有效来源，直接使用，并缓存
            $originId = $srvOrigin->key2id($originKey);
            $srvOrigin->cache($target, $this->uid, $originId);
        } elseif (!$originId = $srvOrigin->cache($target, $this->uid, null)) { //尝试从缓存获取，失败
            $originId = $srvUser->uid2origin($this->uid); //获取用户初始来源
        }
        $unionSn = $this->srvUnion->createGroup($this->uid, $target, $notice,$originId);
        $this->apiSuccess($unionSn);
        if ($unionSn) {
            $this->apiSuccess($unionSn);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to create group union"]);
        }
    }

    public function _GET_list()
    {
        //获取团体订单列表
        $target = $this->apiGET('target', '');
        $list = $this->srvUnion->groupList($this->uid, $target);
        $this->apiSuccess($list);
    }

    public function _GET_detail()
    {
        //团体订单详情
        $unionSn = $this->apiGET('unionSn');
        $data = $this->srvUnion->groupDetail($unionSn);
        $this->apiSuccess($data);
    }

    public function _GET_join()
    {
        //申请加入页面
        $unionSn = $this->apiGET('unionSn');
        $profile = $this->srvUnion->groupProfile($unionSn);
        $this->apiSuccess($profile);
    }

    public function _POST_join()
    {
        //申请加入提交
        $unionSn = $this->apiPOST('unionSn');
        $remark = $this->apiPOST('remark');
        $joinSn = $this->srvUnion->groupJoin($unionSn, $this->uid, $remark);
        $data = [
            'joinSn' => $joinSn
        ];
        $this->apiSuccess($data);
    }

    public function _POST_cancel()
    {
        $unionSn = $this->apiPOST('unionSn');
    }

    public function _POST_accept()
    {
        //通过申请
        $unionSn = $this->apiPOST('unionSn');
        $joinSn = $this->apiPOST('joinSn');
        $status = $this->srvUnion->groupAccept($unionSn, $joinSn);
        $settle = $this->srvUnion->groupSettle($unionSn);
        $data = [
            'settle' => $settle,
            'status' => $status,
        ];
        $this->apiSuccess($data);
    }

    public function _POST_reject()
    {
        //拒接申请
        $unionSn = $this->apiPOST('unionSn');
        $joinSn = $this->apiPOST('joinSn');
        $status = $this->srvUnion->groupReject($unionSn, $joinSn);
        $settle = $this->srvUnion->groupSettle($unionSn);
        $data = [
            'settle' => $settle,
            'status' => $status,
        ];
        $this->apiSuccess($data);
    }

}