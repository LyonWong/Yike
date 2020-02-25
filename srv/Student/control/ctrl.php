<?php


namespace Student;


use Core\unitDoAction;

class ctrl extends ctrlSess
{
    use unitDoAction;


    public function _DO_()
    {
        \view::tpl('idx');
    }

    public function _DO_ok()
    {
        echo 'ok';
    }

    public function _DO_receptor()
    {
        $content = $_GET;
        $content['usn'] = $this->usn;
        \output::debug('receptor', $content, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
    }

}