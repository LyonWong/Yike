<?php

namespace _;

class ctrlWeixin extends ctrlSess
{
    public function _GET_jsConfig()
    {
        $url = $this->apiGET('url');
        $config = new servShare();
        $ret = $config->wxGetConfig(urldecode($url));
        $this->apiSuccess($ret);
    }
}