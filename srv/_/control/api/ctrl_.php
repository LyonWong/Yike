<?php


namespace _\api;


use _\servSession;
use Core\unitAPI;
use Core\unitDoAction;

class ctrl_
{
    use unitDoAction;
    use unitAPI;

    protected $platform = null;

    protected function getUser()
    {
        $sess = ctrlSigned::checkSess();
        $flag = ctrlSigned::flag();

        $uid = servSession::sole($this->platform, $sess['usn'])->check2uid($flag, $sess['token']);
        if ($uid) {
            return [
                'uid' => $uid,
                'usn' => $sess['usn']
            ];
        } else {
            return false;
        }

    }

    public function _DO_()
    {
        $methods = $this->apiRequest('methods');
        $this->apiHold = true;
        $data = [];
        ob_start();
        foreach ($methods as $method) {
            $this->run($method);
            $data[$method] = json_decode(ob_get_clean());
        }
        $this->apiSuccess($data);
    }

}