<?php


namespace Admin;


use Core\unitDoAction;

class ctrlHome extends ctrlSess
{
    use unitDoAction;

    public function _DO_()
    {
        \view::tpl('index', [
            'page' => 'home',
            'info' => 'Welcome',
        ]);
    }
}
