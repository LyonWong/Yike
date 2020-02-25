<?php


namespace _;


class ctrlError
{
    public function run()
    {
        $code = \input::get('code')->value();
        $message = \input::get('message')->value();
        \view::tpl('error', [
            'code' => $code,
            'message' => $message
        ]);
    }

}