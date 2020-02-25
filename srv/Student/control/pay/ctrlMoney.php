<?php


namespace Student\pay;

use Core\library\Tool;
use Student\servMoney;
use Student\servOrder;
use _\servPayoff;

class ctrlMoney extends ctrl_
{
    const ERR_DRAWCASH_BUSY = ['2', '抱歉！提现繁忙，请联系管理员'];

    public function _GET_balance()
    {
        $balance = servMoney::sole($this->platform)->balance($this->uid);
        $this->apiSuccess($balance);
    }

    public function _GET_bill()
    {
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $data = servOrder::sole($this->platform)->bill($this->uid, $cursor, $limit);
        $this->apiSuccess($data);
    }

    public function _GET_debit()
    {
        $cursor = $this->apiGET('cursor', Tool::timeEncode('now'));
        $limit = $this->apiGET('limit', 10);
        $data = servMoney::sole($this->platform)->debit($this->uid, $cursor, $limit);
        $this->apiSuccess($data);
    }

    public function _DO_overview()
    {
        $data = servPayoff::sole($this->platform)->overview($this->uid);
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