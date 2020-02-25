<?php


namespace _;


use _\sign\servWeixin;
use Core\library\Tool;
use Core\unitDoAction;

class ctrlTest extends ctrl_
{
    use unitDoAction;

    public function _DO_()
    {
        \view::tpl('test');
//        print_r($_SERVER);
    }

    public function _DO_ok()
    {
        echo 'ok';
    }

    public function _DO_clear()
    {
        setcookie('sess', '', strtotime('-1 seconds'), '/', '', null, true);
        header("Location: /lesson/home");
    }

    public function _DO_avatar()
    {
        sleep(2);
        header("Content-Type: image/png");
        echo file_get_contents('http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar!avatar?1djr165');
    }

    public function _POST_tim()
    {
        $identifier = \input::post('identifier')->value();
        $room = \input::post('room')->value();
        echo "pending... $room : $identifier";
        fastcgi_finish_request();
        $msg = "Say hi, @" . date('Y-m-d H:i:s')." \r\n ". Tool::genSecret(8);
        #构造高级接口所需参数
        $msg_content = array();
        //创建array 所需元素
        $msg_content_elem = array(
            'MsgType' => 'TIMCustomElem',       //文本类型
            'MsgContent' => array(
                'Data' => $msg,
                'Desc' => 'COMMENT',
                'Ext' => ''
            )
        );
        array_push($msg_content, $msg_content_elem);
        $tim = servTIM::sole(null, servTIM::adminAccount())->tim();
        $res = $tim->group_send_group_msg2($identifier, $room, $msg_content);
        \output::debug('tim', [
            'identifier'=>$identifier,
            'room' => $room,
            'message' => $msg,
            'res' => $res
        ], DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
    }

    public function _DO_login()
    {
        $res = servWeixin::buildRequestUrl('/');
        echo urldecode($res);
    }

}