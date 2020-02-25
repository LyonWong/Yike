<?php


namespace _\api;



use _\servCooperate;

class ctrlCooperate extends ctrl_
{
    public function _GET_wxa($opt)
    {
        switch ($opt) {
            case 'source':
                $appid = $this->apiGET('appid');
                $source = servCooperate::sole($this->platform)->getWxaSource($appid);
                $this->apiSuccess([
                    'source' => $source
                ]);
                break;
            default:
                break;
        }
    }

}