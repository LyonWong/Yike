<?php

namespace _\_;


use _\servTIM;

class ctrlTim extends ctrl_
{
    public function _POST_gen_user_sig()
    {
//        $platform = $this->apiPOST('platform','');
        $identifier = $this->apiPOST('identifier');
        $sig = servTIM::genUserSig($identifier);
        $this->apiSuccess($sig);
    }


}