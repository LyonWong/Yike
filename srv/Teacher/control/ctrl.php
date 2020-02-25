<?php


namespace Teacher;


class ctrl extends ctrlSess
{
    public function _DO_()
    {
        \view::tpl('idx');
    }

    public function _DO_ok()
    {
        echo 'ok';
    }

}
