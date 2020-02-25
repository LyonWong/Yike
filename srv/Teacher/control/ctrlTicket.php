<?php


namespace Teacher;

use _\servRefund;
use Core\unitAPI;

class ctrlTicket extends ctrlSess
{
    use unitAPI;


    public function _GET_refundApplyList()
    {
        $limit = intval($this->apiGET('limit', 10));
        $page = intval($this->apiGET('page', 1));
        $status = intval($this->apiGET('status', 0));
        $ret = servRefund::sole($this->platform)->applyList($this->uid, $page, $limit,$status);
        $this->apiSuccess($ret);


    }

    public function _POST_doRefundApply()
    {
        $remark = $this->apiPOST('remark', '');
        $operate = intval($this->apiPOST('operate', 0));
        $id = $this->apiPOST('id');
        $ret = servRefund::sole($this->platform)->dealApply($this->uid, $remark, $operate, $id);
        if ($ret) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

}