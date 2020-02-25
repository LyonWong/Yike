<?php


namespace _\weixin;

use _\data;
use _\dataLesson;
use _\dataLessonAccess;
use _\dataSettings;
use _\dataUnionOrder;
use _\dataUser;
use _\dataUserAuth;
use _\dataOrder;
use _\servLesson;
use _\servOrder;
use _\servPromote;
use _\servSettings;
use _\servUnionOrder;
use _\servUser;
use _\sign\servWeixin;
use Core\library\Tool;
use Core\unitInstance;
use Core\unitHttp;
use Core\library\Http;


class servMp extends serv_
{
    use unitInstance;
    use unitHttp;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }


    public function __construct($platform = null)
    {
        parent::__construct($platform);
    }

    public function doRecharge($out_trade_no, $transaction_id, $paid_amount)
    {
        if (
            strpos($out_trade_no, data::SN_ORDER) === 0 &&
            $orderInfo = dataOrder::sole($this->platform)->fetchOne(['sn' => $out_trade_no], 'uid,i_type,lesson_id,order_amount,origin_id,i_status')
        ) {
            if ($orderInfo['i_status'] > 0) {
                return true;
            } elseif ($orderInfo['i_type'] == dataOrder::TYPE_ADMIRE) {
                servLesson::sole($this->platform)->admire($orderInfo['uid'], $orderInfo['lesson_id'], $paid_amount);
                dataorder::sole($this->platform)->update(['i_status' => dataOrder::STATUS_DONE], ['sn' => $out_trade_no]);
            } else {
                $res = servOrder::sole($this->platform)->purchase($out_trade_no, $paid_amount, [
                    'pay_sn' => $transaction_id,
                    'i_pay_way' => dataOrder::PAY_WAY_WEIXIN,
                ]);
                if ($res) {
                    $lesson = servLesson::sole($this->platform)->id2info($orderInfo['lesson_id'], ['sn', 'i_form']);
                    if ($lesson['i_form']!=dataLesson::FORM_ARTICLE ) { // 除文章外，推送折扣券
                        servPromote::sole($this->platform)->sendPromoteMsg($orderInfo['uid'], $lesson['sn']);
                    }
                }
                return $res;
            }
        }
        if (
            strpos($out_trade_no, data::SN_UNION_ORDER) === 0 &&
            $orderInfo = dataUnionOrder::sole($this->platform)->inquireOne(['sn' => $out_trade_no], ['uid', 'i_status', 'extra'])
        ) {
            if ($orderInfo['i_status'] > 0) {
                return true;
            } else {
                $res = servUnionOrder::sole($this->platform)->purchase($out_trade_no, $paid_amount, [
                    'pay_sn' => $transaction_id,
                    'i_pay_way' => dataUnionOrder::PAY_WAY_WEIXIN
                ]);
                if ($res) {
                    if ($seriesSn = $orderInfo['extra']['series_sn']) {
                        servPromote::sole($this->platform)->sendPromoteMsg($orderInfo['uid'], $seriesSn);
                    }
                }
                return $res;
            }
        }
        /*
        if ($orderInfo['order_amount'] == $paid_amount) {
            dataOrder::sole($this->platform)->update(['paid_amount' => $paid_amount, 'pay_sn' => $transaction_id, 'i_status' => 1], ['sn' => $out_trade_no])->rowCount();
            if (!dataLessonAccess::sole($this->platform)->fetchOne(['lesson_id' => $orderInfo['lesson_id'], 'uid' => $orderInfo['uid'], 'i_event' => dataLessonAccess::EVENT_ENROLL], 'id')) {
                servLesson::sole($this->platform)->varEnroll($orderInfo['lesson_id'], $orderInfo['origin_id']);
                servLesson::sole($this->platform)->varIncome($orderInfo['lesson_id'], $orderInfo['origin_id'], $paid_amount);
                servMpMsg::sole($this->platform)->sendEnrollMsg(\_\servLesson::sole($this->platform)->id2sn($orderInfo['lesson_id']), $orderInfo['uid']);
                return dataLessonAccess::sole($this->platform)->append($orderInfo['lesson_id'], $orderInfo['uid'], dataLessonAccess::EVENT_ENROLL, ['origin' => $orderInfo['origin_id']]);
            }
            return true;
        } else {
            return false;
        }
        */

    }

    /**
     * 公众号授权登录
     * @param $code
     * @param $originKey
     * @return bool|int
     */
    public function weixinLogin($code, $originKey)
    {
        $userInfo = $this->code2Info($code, 'mp');
        if (!$userInfo) {
            return false;
        }
        $uid = 0;
        $userInfo = json_decode($userInfo, true);
        if (isset($userInfo['unionid'])) {
            $res = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_WEIXIN, $userInfo['unionid']);
            if (!$res) {
                $uid = $this->saveAccount($userInfo, $originKey);
            } else {
                dataUser::sole($this->platform)->update(['tms_update' => date('Y-m-d H:i:s')], ['id' => $res['uid']]);
                $info = json_decode(dataUser::sole($this->platform)->fetchOne(['id' => $res['uid']], 'info', 'info'), true);
                if (!isset($info['openid'])) {
                    $info['openid'] = $userInfo['openid'];
                    dataUser::sole($this->platform)->update(['info' => json_encode($info)], ['id' => $res['uid']]);
                }
                $uid = $res['uid'];
            }
        }
        return $uid;
    }


    public function dealText($fromUserName, $toUserName, $text)
    {

        switch ($text) {
            case '发票':
                $ret = servMp::sole($this->platform)->getInvoice($fromUserName, $toUserName);
                break;
            case '开启自动退款':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::AUTO_REFUND, 1);
                break;
            case '关闭自动退款':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::AUTO_REFUND, 0);
                break;
            case '恢复自动退款':
                servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::AUTO_REFUND, 1);
                $ret = '自动退款已恢复至7天，回复「延长自动退款」可延长至30天';
                break;
            case '延期自动退款':
                servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::AUTO_REFUND, 0);
                $ret = '自动退款已延长至30天，回复「恢复自动退款」可还原至7天';
                break;
            case '开启课前提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_PRECLASS, 1);
                break;
            case '关闭课前提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_PRECLASS, 0);
                break;
            case '开启未听课提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_ABSENCE, 1);
                break;
            case '关闭未听课提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_ABSENCE, 0);
                break;
            case '开启佣金提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_COMMISSION, 1);
                break;
            case '关闭佣金提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_COMMISSION, 0);
                break;
            case '开启结算提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_PAYOFF, 1);
                break;
            case '关闭结算提醒':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_PAYOFF, 0);
                break;
            case '开启留言通知':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_BOARD, 1);
                break;
            case '关闭留言通知':
                $ret = servUser::sole($this->platform)->userSetByopenId($fromUserName, dataUser::NOTICE_BOARD, 0);
                break;
            default:
                $res = servSettings::sole($this->platform)->match(dataSettings::TYPE_MP_REPLY, $text);
                $pre = [
                    'ToUserName' => $fromUserName,
                    'FromUserName' => $toUserName,
                    'CreateTime' => time(),
                ];
                if (isset($res['datum'])) {
                    $data = array_merge($pre, $res['datum']);
                    return Tool::xmlEncode(['xml' => $data]);
                } else {
                    return null;
                }
                break;
        }
        if ($ret) {
            $ret = '
                    <xml>
                    <ToUserName><![CDATA[' . $fromUserName . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $toUserName . ']]></FromUserName>
                    <CreateTime>' . time() . '</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[' . $ret . ']]></Content>
                    </xml>';
        }
        return $ret;
    }

    /**
     * 关注/取关事件
     * @param $openId
     * @param $fromUser
     * @return bool
     * @throws \coreException
     */
    public function subscribe($openId, $fromUser)
    {
        if (!$info = $this->info($openId)) {
            return false;
        }

        $uaid = $info['unionid'];
        unset($info['subscribe_time'],
            $info['remark'],
            $info['groupid'],
            $info['tagid_list'],
            $info['qr_scene'],
            $info['qr_scene_str']
        );

        $srvSignWx = servWeixin::sole($this->platform);

        if ($auth = $srvSignWx->check(dataUserAuth::TYPE_WEIXIN, $uaid)) { // 有公众号授权
            $uid = $auth['uid'];
            $srvSignWx->save($uid, $info);
        } elseif ($uid = $srvSignWx->assoc(dataUserAuth::TYPE_WEIXIN, $uaid, $openId, dataUserAuth::TYPE_WXA)) { // 尝试关联小程序足球
            $srvSignWx->save($uid, $info);
        } else { // 新用户
            $uid = $srvSignWx->create(dataUserAuth::TYPE_WEIXIN, $uaid, $info['openid'], $info['name'], '_', $info);
        }

        if ($le = dataLessonAccess::sole($this->platform)->lastEvent($uid, [dataLessonAccess::EVENT_ENROLL])) {
            $ls = dataLesson::sole($this->platform)->fetchOne(['id' => $le['lesson_id']], ['sn', 'category']);
            $targetSn = $ls['category'] ?: $ls['sn'];
            servPromote::sole($this->platform)->sendPromoteMsg($uid, $targetSn);
        }

        $usn  = servUser::sole($this->platform)->uid2usn($uid);
        if ($targetSn = data::redis()->get("PROMOTE_HAGGLE_$usn")) {
            servPromote::sole($this->platform)->sendHaggleMsg($uid, $targetSn);
        }

        /*
        $ret = dataUserAuth::sole($this->platform)->search(dataUserAuth::TYPE_WEIXIN, $info['unionid']);
        if ($ret) {
            $userInfo = json_decode(dataUser::sole($this->platform)->fetchOne(['id' => $ret['uid']], 'info', 'info'), true);
            $userInfo['subscribe'] = 1;
            $userInfo['openid'] = $info['openid'];
            dataUser::sole($this->platform)->update(['info' => json_encode($userInfo)], ['id' => $ret['uid']]);
            if ($le = dataLessonAccess::sole($this->platform)->lastEvent($ret['uid'], [dataLessonAccess::EVENT_ENROLL])) {
                $ls = dataLesson::sole($this->platform)->fetchOne(['id' => $le['lesson_id']], ['sn', 'category']);
                $targetSn = $ls['category'] ?: $ls['sn'];
                servPromote::sole($this->platform)->sendPromoteMsg($ret['uid'], $targetSn);
            }
        } else {
            // 关注的时候生成内部帐号
            unset($info['subscribe_time'], $info['remark'], $info['groupid'], $info['tagid_list']);
            $this->saveAccount($info);
        }
        */

        return $ret = '
                    <xml>
                    <ToUserName><![CDATA[' . $openId . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $fromUser . ']]></FromUserName>
                    <CreateTime>' . time() . '</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image><MediaId><![CDATA[DApxZDH6QmgkRfVC-cK4uOTBuOkWF9Y3z0JjroqNZhI]]></MediaId></Image>
                    </xml>';

        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
        $content = "你好，欢迎来到【易灵微课】\r\n
易灵微课是一个基于微信的在线学习平台，提供高质量课程内容，一小时内无条件退款，让你放心学习，收获未来。\r\n
点击<a href='https://$domain/'>课程列表</a> ，开启知识之旅。
";
        return $ret = '
                    <xml>
                    <ToUserName><![CDATA[' . $openId . ']]></ToUserName>
                    <FromUserName><![CDATA[' . $fromUser . ']]></FromUserName>
                    <CreateTime>' . time() . '</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[' . $content . ']]></Content>
                    </xml>';

    }

    public function unSubscribe($openId)
    {
        if (!$info = $this->info($openId)) {
            return false;
        }
        $srvSignWx = servWeixin::sole($this->platform);
        $auth = $srvSignWx->check(dataUserAuth::TYPE_WEIXIN, $info['unionid']);
        if ($auth) {
            $uid = $auth['uid'];
        } else {
            $uid = servUser::sole($this->platform)->openid2uid($openId);
        }
        if ($uid) {
            return (bool)$srvSignWx->save($uid, ['subscribe' => 0]);
//            $userInfo = json_decode(dataUser::sole($this->platform)->fetchOne(['id' => $uid], 'info', 'info'), true);
//            $userInfo['subscribe'] = 0;
//            dataUser::sole($this->platform)->update(['info' => json_encode($userInfo)], ['id' => $uid]);
        } else {
            return false;
        }
    }

    public function isSubscribe($uid)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openId . '&lang=zh_CN';
        $ret = Http::inst()->get($url);
        $result = json_decode($ret, true);
        $info = dataUser::sole($this->platform)->fetchOne(['id' => $uid], 'info', 'info');
        $info = json_decode($info, true);
        if (isset($result['subscribe']) && $result['subscribe']) {
            $info['subscribe'] = 1;
            dataUser::sole($this->platform)->update(['info' => json_encode($info)], ['id' => $uid]);
        } else {
            $info['subscribe'] = 0;
            dataUser::sole($this->platform)->update(['info' => json_encode($info)], ['id' => $uid]);
        }

        return $info['subscribe'];
    }

    public function getInvoice($openId, $fromUser)
    {
        $content = "您好，在易灵微课累计消费50以上，可开电子发票；累计消费500以上，可开纸质发票
请按以下格式回复发票信息 \r\n
抬头：//必填，公司/个人
税号：//仅抬头为公司时需要
形式：//电子版/纸质版
地址：//电子版填接收邮箱，纸质版填快递地址
";
        return $content;
    }


    public function createMenu($buttonData)
    {

        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $accessToken;

        $ret = Http::inst()->post($url, $buttonData);
        $data = json_decode($ret, true);
        if ($data['errcode'] == 0) {
            self::setMenuData($buttonData);
        }
        return $data;


    }

    public function getMenuData()
    {
        return data::redis()->get('wechat_menu_button');
    }

    private function setMenuData($button)
    {
        return data::redis()->set('wechat_menu_button', $button);
    }

    public function deleteMenu()
    {
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $accessToken;
    }

    public function sendCustomMessage($uid, $type, $content)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, "openid")['openid'];
        $data = [
            'touser' => $openId,
            'msgtype' => $type,
            $type => $content,
        ];
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $accessToken;
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = Http::inst()->post($url, $data);
        return $res;
    }


}