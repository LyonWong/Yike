<?php


namespace Admin\develop;


use _\servDWZ;
use _\servQiniu;
use _\servTrigger;

class ctrlQrcode extends ctrl_
{
    protected $scopeKey = 'develop-qrcode';

    public function _DO_()
    {
        \view::tpl('page', [
            'page' => 'develop/qrcode'
        ]);
    }

    public function _GET_ShortUrl()
    {
        $url = $this->apiGET('url');
        $ret = servDWZ::sole($this->platform)->convert2ShortUrl($url);
        $this->apiSuccess($ret);

    }

    public function _GET_Build()
    {
        $url = $this->apiGET('url');
        $logoUrl = \view::upload('card/logo');
        header("Content-type: image/png");
        servQiniu::inst()->getQrcode($url,$logoUrl);
    }


}