<?php


namespace _\_;


use _\config;
use _\weixin\serv;

class ctrlWeixin extends ctrl_
{
    protected $platform;

    public function _POST_access($type)
    {
        $secret = $this->apiPOST('secret');
        $allowedSecrets = config::load('weixin', 'base', 'FetchAccessSecrets', []);
        if (in_array($secret, $allowedSecrets)) {
            $data = serv::sole($this->platform)->fetchAccessToken($type);
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_ILLEGAL_SECRET, [$secret]);
        }
    }

}