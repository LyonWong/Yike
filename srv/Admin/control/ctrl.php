<?php


namespace Admin;


use Core\unitDoAction;

class ctrl extends ctrlSess
{
    use unitDoAction;

    public function _DO_()
    {
        \view::tpl('page', [
            'page' => 'page-',
            'info' => 'Welcome!'
        ]);
    }

    public function _DO_init()
    {
        $init = \config::load('boot','init');
        echo json_encode($init);
    }

    public function _DO_error()
    {
        \output::debug('Admin-error', implode(LF, $_GET), DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
    }

    public function _DO_debug()
    {
        \output::debug('Admin-debug', implode(LF, $_GET), DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
    }


}