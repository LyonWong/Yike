<?php


namespace _\api;


use _\weixin\servJS;

class ctrlJweixin extends ctrl_
{
    public function _GET_config()
    {
        $url = $this->apiGET('url', "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
        $res = servJS::sole($this->platform)->config($url);
        $this->apiSuccess($res);
    }
}