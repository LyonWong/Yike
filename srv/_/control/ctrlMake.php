<?php


namespace _;


use Core\library\QRcode;
use Core\unitDoAction;

class ctrlMake extends ctrl_
{
    use unitDoAction;

    public function _GET_qrCode()
    {
        $text = \input::get('text')->value();
        $text = base64_decode($text);
        Header("Content-type: image/png");
        QRcode::png($text, false, QR_ECLEVEL_H, 7, 1);
    }

}