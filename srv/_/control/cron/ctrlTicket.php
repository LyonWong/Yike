<?php


namespace _\cron;


use _\servTicket;

class ctrlTicket extends ctrl_
{
    public function _DO_hourly()
    {
        $this->_DO_UntreatedRefundApply();
    }

    public function _DO_UntreatedRefundApply()
    {
        $deadline = \input::cli('dateline', "-3 days")->toDate('Y-m-d H:i:s');
        echo $deadline;
        $count = servTicket::sole(null)->refundApplyToAppeal($deadline);
        $this->runCheck("UntreatedRefund: $count");
    }

}