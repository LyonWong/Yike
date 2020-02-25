<?php


namespace _\cli;


use _\dataUser;
use _\servTIM;
use Core\library\Http;

class ctrlTim extends ctrl_
{
    protected $tim;

    protected $serv;

    public function __construct()
    {
        parent::__construct();
        $this->serv = servTIM::sole(null, servTIM::adminAccount());
        $this->tim = $this->serv->tim();
    }

    public function _DO_()
    {
    }

    public function _DO_create()
    {
        $res = $this->tim->group_create_group('ChatRoom', 'L5900888378d29-D', '58f5e18810316');
        print_r($res);
    }

    public function _DO_modify()
    {
        $group = \input::cli('group')->value();
        $info_set = [
            'max_member_num' => 8
        ];
        $res = $this->tim->group_modify_group_base_info2($group, $group, $info_set, []);
        print_r($res);
    }

    public function _DO_account_import()
    {
        $identifier = \input::cli('identifier')->value();
        $name = \input::cli('name', $identifier)->value();
        $avatar = \input::cli('avatar', '')->value();
        $res = $this->tim->account_import($identifier, $name, $avatar);
        print_r($res);
    }

    public function _DO_usersig()
    {
        $res = $this->tim->get_user_sig();
        echo $res . "\n";
    }

    public function _DO_noSpeak()
    {
        $data = [
            "Set_Account" => 'admin',
            "GroupmsgNospeakingTime" => 7200
        ];
        $res = $this->serv->api('openconfigsvr', 'setnospeaking', $data);
        print_r($res);
    }

    public function _DO_profile()
    {
        $usn = \input::cli('usn')->value(true);
        $res = $this->tim->profile_portrait_get2([$usn], [
            'Tag_Profile_IM_Image',
            'Tag_Profile_IM_Nick',
            'Tag_Profile_IM_Gender']);
        print_r($res);
    }

    public function _DO_push()
    {
        //$res = $this->tim->group_send_group_system_notification2('L591a745be656f-T', json_encode(['a'=>'A','b'=>'B']), []);
//        $res = $this->tim->group_send_group_msg('admin', 'L5900888378d29-T', 'hello');
//        $res = servTIM::sole(null)->systemMessage('L591a745be656f-T', 'hint', '老师暂时离开');
        $res = servTIM::sole(null)->systemMessage('L598bfa8b8da85-D', 'hint', '老师暂时离开');
        print_r($res);
//        $res = servTIM::sole(null)->systemMessage('L598bfa8b8da85-T', 'hint', '老师暂时离开');
//        print_r($res);
    }

    public function _DO_send()
    {
        $identifier = \input::cli('identifier', '__SYSTEM__')->value();
        $groupId = \input::cli('groupId')->value(true);
        $type = \input::cli('type', 'hint')->value();
        $data = \input::cli('data')->value(true);
        $text = json_encode([
            'type' => $type,
            'data' => $data
        ]);

        #构造高级接口所需参数
        $msg_content = array();
        //创建array 所需元素
        $msg_content_elem = array(
            'MsgType' => 'TIMCustomElem',       //文本类型
            'MsgContent' => array(
                'Data' => $text,
                'Desc' => '__SYSTEM__',
                'Ext' => ''
            )
        );
        array_push($msg_content, $msg_content_elem);
        $tim = servTIM::sole(null, servTIM::adminAccount())->tim();
        $res = $tim->group_send_group_msg2($identifier, $groupId, $msg_content);
        print_r($res);
    }

    public function _DO_delete()
    {
        $group = \input::cli('group')->value();
        $usn = \input::cli('usn')->value();
        $res = $this->tim->group_delete_group_member($group, $usn, 1);
        print_r($res);
    }

    public function _DO_online()
    {
        $res = $this->tim->openim_querystate(['U59048ee5624d8', '58f82f43a33f1', 'U59375e4144354']);
        print_r($res);
    }

    public function _DO_flood()
    {
        $room = \input::cli('room')->value(true);
        $num = \input::cli('num', 1)->value();
        $hz = \input::cli('hz', 1)->value();
        $limit = \input::cli('limit', 1)->value();
        $init = \input::cli('init')->value();

        echo "flooding $room" . LF;

        echo "init robots" . LF;
        $bots = [];
        while ($num--) {
            $_busn = 'B-' . $num;
            if ($init) {
                $this->tim->account_import($_busn, $_busn, 'http://oorfr7tm0.bkt.clouddn.com/Android.png');
            }
            $bots[] = $_busn;
        }

        $domain = \_\config('boot', 'public', 'domain');

        while ($limit) {
            $Bis = (array)array_rand($bots, $hz); //取出这一秒使用的机器人
            foreach ($Bis as $i) {
                $URL = "http://$domain/test-tim";
                $res = Http::inst()->post($URL, [
                    'identifier' => $bots[$i],
                    'room' => $room,
                ]);
                echo "$bots[$i]: $res" . LF;
                $limit--;
            }
            sleep(1);
        }
    }

    public function _DO_joins($n=1)
    {
        $uid = \input::cli('start', 1)->value();
        $group = \input::cli('group')->value(true);
        $daoUser = dataUser::sole($this->platform);
        do {
            $usn = $daoUser->fetchOne(['id' => $uid], ['sn'], 0);
            $res = $this->tim->group_add_group_member($group, $usn, 1);
            \output::debug("temp", "$usn join $group ".json_encode($res), DEBUG_SLOT_TEMP);
            $uid++;
        } while (--$n);
    }

}