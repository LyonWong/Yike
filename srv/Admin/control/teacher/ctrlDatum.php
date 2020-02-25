<?php


namespace Admin\teacher;


class ctrlDatum extends ctrl_
{
    public function _DO_()
    {
        \view::tpl('page', [
            'page' => 'teacher/datum',
            'info' => 'datum',
        ]);
    }

}