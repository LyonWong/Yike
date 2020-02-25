<?php
namespace Admin\pay;


use Admin\servOrder;

class ctrlDetail extends ctrl_
{


    public function _DO_()
    {
        $orderSn = \input::get('order_sn')->value();
        $detail = servOrder::sole($this->platform)->detail($orderSn);
        \view::tpl('page', [
            'page' => 'pay/detail',
        ])->with('detail', $detail);
    }
}