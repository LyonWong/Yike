<?php


namespace Admin\pay;


use Admin\servPayoff;

class ctrlPayoff extends ctrl_
{
    public function _DO_()
    {
        $uid = \input::get('uid')->value();
        $orderId = \input::get('orderId')->value();
        $list = servPayoff::sole($this->platform)->query($uid, $orderId);
        \view::tpl('page', [
            'page' => 'pay/payoff',
            'query' => [
                'uid' => $uid,
                'orderId' => $orderId
            ]
        ])->with('list', $list);
    }

}