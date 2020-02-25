<?php


namespace Teacher;


class ctrlRevenue extends ctrlSess
{
    const ERR_DRAWCASH_BUSY = ['2', '抱歉！提现繁忙，请联系管理员'];

    public function _DO_overview()
    {
        $data = servPayoff::sole($this->platform)->overview($this->uid);
        $this->apiSuccess($data);
    }

    public function _DO_record($item=null)
    {
        $page = $this->apiGET('page', 1);
        $limit = $this->apiGET('limit', 10);
        $data = servPayoff::sole($this->platform)->record($this->uid, $item, $page, $limit);
        $this->apiSuccess($data);
    }

    public function _POST_drawcash()
    {
        $amount = $this->apiPOST('amount');
        $amount = round($amount*100);
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
                $this->apiFailure(self::ERR_DRAWCASH_BUSY);
            } else {
                $this->apiFailure(self::ERR_UNDEFINED, [$result->message]);
            }
        }
    }

}