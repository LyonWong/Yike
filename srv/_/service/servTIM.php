<?php


namespace _;

require_once PATH_ROOT . '/library/TimServerSdk/TimRestApi.php';

use Core\library\Http;
use Core\unitInstance;

class servTIM extends serv_
{
    use unitInstance;

    const IDENTIFIER_ADMIN = '__ADMIN__';

    const SYS_MSG_HINT = 'hint';
    const SYS_MSG_NOTE = 'note';

    protected $tim;

    protected $identifier;

    /**
     * @param $platform
     * @param $identifier
     * @return servTIM
     */
    public static function sole($platform, $identifier = self::IDENTIFIER_ADMIN)
    {
        return self::_singleton($platform, $identifier);
    }

    public static function adminAccount()
    {
        return config::load('tencent', 'im', 'AccountAdmin');
    }

    public function __construct($platform, $identifier)
    {
        parent::__construct($platform);
        $this->tim = new \TimRestAPI();
        $SdkAppId = config::load('tencent', 'im', 'SdkAppId', '_');
//        $PrivateKey = config::load('tencent', 'im', 'PrivateKey');
        if ($identifier === self::IDENTIFIER_ADMIN) {
            $identifier = self::adminAccount();
        }
        $this->identifier = $identifier;
        $this->tim->init($SdkAppId, $identifier);
        if (function_exists('exec')) {
            $this->tim->set_user_sig(self::genUserSig($identifier));
        } else {
            $this->fetchUserSig($identifier);
        }
    }

    public function tim()
    {
        return $this->tim;
    }

    public function api($service, $cmd, $data)
    {
        return $this->tim->api($service, $cmd, $this->identifier, $this->tim->get_user_sig(), json_encode($data));
    }

    public static function genUserSig($identifier)
    {
        $SdkAppId = config::load('tencent', 'im', 'SdkAppId');
        $PrivateKey = config::load('tencent', 'im', 'PrivateKey');
        $tim = new \TimRestAPI();
        $tim->init($SdkAppId, $identifier);
        $tim->generate_user_sig($identifier, SECONDS_DAY * 7, $PrivateKey, PATH_ROOT . '/library/TimServerSdk/signature/linux-signature64');
        return $tim->get_user_sig();
    }

    public function fetchUserSig($identifier)
    {
        $domain = config::load('boot', 'public', 'domain');
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $res = Http::inst()->post("$scheme://$domain/_/tim-gen_user_sig", [
            'identifier' => $identifier
        ]);
        $result = json_decode($res, true);
        if ($result && $result['error'] == 0) {
            $sig = $result['data'];
            $this->tim->set_user_sig($sig);
            return $sig;
        } else {
            return false;
        }
    }

    public function systemMessage($groupId, $type, $data, $receiver = [])
    {
        $content = [
            'type' => $type,
            'data' => $data,
        ];
        $res = $this->tim->group_send_group_system_notification2($groupId, json_encode($content), $receiver);
        \output::debug('tim', "system-mesage@$groupId:[".json_encode($receiver) .']' . json_encode($content) . '|' . json_encode($res), DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        return $res;
    }

    public function quoteMessage($groupId, $identifier, $data)
    {
        $msg_content = array();
        //创建array 所需元素
        $msg_content_elem = array(
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => array(
                'Data' => $data,
                'Desc' => 'QUOTE',
                'Ext' => ''
            )
        );
        array_push($msg_content, $msg_content_elem);
        $res = $this->tim->group_send_group_msg2($identifier, $groupId, $msg_content);
        return $res;
    }

    public function forbidSendMsg($groupId, $member, $shutUpTime = 3600)
    {
        $res = $this->tim->group_forbid_send_msg($groupId, $member, $shutUpTime);
        return $res;
    }

    public function deleteGroupMember($groupId, $member, $silence = 0)
    {
        $res = $this->tim->group_delete_group_member($groupId, $member, $silence);
        return $res;
    }
}