<?php

/**
 * 微信模板消息
 */

namespace Core\library;
use _\weixin\serv;

class WechatTpl
{
    protected static $custom_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';
    protected static $appid = null;
    protected static $appsecret = null;
    protected static $access_token = NULL;
    protected static $_template_data = array(
        'deal_result' => array (
            'id' => 'lbszHMPxEqf7fVKb5BKyp_-WwW6qI2Yugp-k7igYtzQ',
            'first' => 'hello',
            'reason' => 'reason',
            'result' => 'result',
            'remark' => 'remark',
            'url' => '',
        ),

        //课程报名成功
        'enroll_success' => array(
            'id' => 'JKa8S3UGQY1rIhVGnBCTU698SlJ_r0KkrKFOAyfQhDo',
            'first' => '您好，xxx',
            'lesson_title' => 'xx课程', //报名课程
            'enroll_time' => '2015-12-22 12:00', //报名时间
            'remark' => '',
            'url' => ''
        ),
        //退款通知
        'refund_notice' => array(
            'id' => 'YbZgPvtNwVUHM0FdJ_tZC1eLh4bumWNRoRBXjELeEUA',
            'first' => '你的退款申请已通过',
            'keyword1' => 'xxxx', //退款状态
            'keyword2' => 'xxxx', //退款课程
            'keyword3' => 'xxxx', //退款金额
            'remark' => '',
            'url' => ''
        ),
        //审核结果通知
        'review_notice' => array(
            'id' => '_Ubhoa4K4emfZ-Z4go6x6iN5W3P9LbixFnX6EjSO2zY',
            'first' => '退款',
            'project' => 'xxxx', //审核项目
            'result' => 'xxxx', //审核结果
            'time' => '2015-12-12', //审核时间
            'remark' => '',
            'url' => ''
        ),
        //预约课程开始提醒
        'lesson_upcome_notice' => array(
            'id' => 'LN32PEPO09C0VXI0YBnmcxIZPYDHnd-xJSgY6jrAc4M',
            'first' => '你预约的课程已开始',
            'keyword1' => 'xxxx', //课程名称
            'keyword2' => 'xxxx', //开课时间
            'remark' => '',
            'url' => ''
        ),
        //反馈结果通知
        'feedback_notice' => array(
            'id' => 'gR_X6BKDhiJuoF-KL6weCCa6YMTY1VFKYxWOifa51AQ',
            'first' => '你的反馈与建议已有处理结果。',
            'keyword1' => '详情页评论区未显示我的评论', //反馈意见
            'keyword2' => '经过查询，是账号异常情况，现已修复。', //处理结果
            'remark' => '谢谢你的关注与支持，如还有疑问，请在建议与反馈页继续反馈。',
            'url' => ''
        ),
        //课程变更通知
        'change_notice' => array(
            'id' => '99nIKk54d1O8UQ_0ZLAmvK6y0OvFM9nSmJ0wdDg9dJo',
            'first' => '你报名的以下课程发生变更。',
            'keyword1' => 'PS入门基础宋大师亲授》', //课程名称
            'keyword2' => '取消开课', //变更事项
            'remark' => '更多相关课程，快来选课吧~',
            'url' => ''
        ),
        //课程返现通知
        'cash_back_notice' => array(
            'id' => 'vc0bI0cd7LKbSoZHFD-cb_52BpiM_JNLX3C_-aZPdzI',
            'first' => '恭喜您获得课程返现',
            'keyword1' => 'xxx》报名费返现', //返现原因
            'keyword2' => '100元', //返现金额
            'keyword3' => '易灵微课账户余额', //返现方式
            'remark' => '账户余额可抵扣任意课程报名费，欢迎收听更多课程',
            'url' => ''
        ),
        //提现成功通知
        'enchashment_notice' => array(
            'id' => 'bF0hP6cnMCEsKVwrm_tUeOqjoAcj_GnecvmUJPQKDHA',
            'first' => '提现资金已到账',
            'money' => '￥100', //提醒金额
            'timet' => '2017-08-08 14：00', //到账时间
            'remark' => '',
            'url' => ''
        ),
        //课程通知
        'listen_notice' => array(
            'id' => '3_zP15az9HQBLS-6_BwzyCSuRFCwcGVwXwU1Jc6Fx0Q',
            'first' => '您报名的课程将在1小时后开始',
            'keyword1' => '30天学好英语', //返现原因
            'keyword2' => '易灵微课', //返现金额
            'keyword3' => '新东方', //返现方式
            'keyword4' =>'X月X日XX：XX',
            'remark' => '点击详情查看课程内容。',
            'url' => ''
        ),
        //呼叫
        'call_notice' => array(
            'id' => 'rQx1uX-yIe7K2qP0bwWWZv1T4ERsmKldhW5aM5va00k',
            'first' => '您好，xxx',
            'keyword1' => 'xx', //姓名
            'keyword2' => 'X月X日XX：XX', //呼叫时间
            'keyword3' => '188', //电话
            'remark' => '',
        ),
        'commission_notice' => array (
            'id' => 'yEZxmEKCrXTLrCGG_AA3ykD8PYSjv2zeqxjoKJLsTk0',
            'first' => '',
            'amount' => '',
            'time' => '',
            'remark' => '',
        ),
        'to_user_notice' => array (
            'id' => '5IGzojjx3q8JCTduaBmNzTxW_iJ52uNYPFFhZa1c41Y',
            'first' => '',
            'keyword1' => '', //信息类型
            'keyword2' => '', //提交日期
            'remark' => '',
        ),
        //收入结算提醒
        'income_settlement_notice' => array(
            'id' => 'oVdFiIT-626rax2sfGprAmzohYFhOFiFMU-DMrRsxBw',
            'first' => '您的账户资金有变动',
            'keyword1' => '2018-010-02 xx:xx:xx', //变动时间
            'keyword2' => '1', //变动金额
            'keyword3' => '0.1', //账户余额
            'remark' => '点击详情查看。',
            'url' => ''
        ),
        //课程进度提醒
        'lesson_progress' => array (
            'id' => 'zmKl9YIfUvmCo6UkM-0X2-xOE8SNjIn8TyalgJasjss',
            'first' => '',
            'keyword1' => '',
            'keyword2' => '',
            'keyword3' => '',
            'remark' => ''
        )

    );

    public function __construct($index = 'mp')
    {
        self::$appid = \config::load('weixin', $index, 'AppID', '', '_');
        self::$appsecret = \config::load('weixin', $index, 'AppSecret', '', '_');
    }

    /***
     * 发送模板消息
     * @param array $data warn类型跟内容
     * @param string $openid
     * @return boolean
     */
    public static function send($data, $openid)
    {
//        self::getToken();
//        $redis = Redis::inst('student', '_');
        $srvWx = serv::sole('');
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $srvWx->getAccessToken('mp');
        $data = self::template($data['channel'], $openid, $data);
        if ($data == false) {
            return false;
        }
        $result = self::https_request($url, $data);
//        file_put_contents('/tmp/msg.log', $result . PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);

        $resultObj = json_decode($result);
        \output::debug('wxmsg', [
            "openid" => $openid,
            "data" => json_decode($data),
            "response" => $resultObj
        ], DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
//        return $resultObj ;
        if ($resultObj->errcode == 0) {
            return true;
        } else {
            if ($resultObj->errcode == 40001) {
//                $redis->del('access_token');
//                self::getToken();
                $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $srvWx->getAccessToken('mp');
                $result = self::https_request($url, $data);
                $resultObj = json_decode($result);
                if ($resultObj->errcode == 0) {
                    return true;
                } else {
                    $errMsg = isset($resultObj->errmsg) ? 'errcode:' . $resultObj->errcode . ' errmsg:' . $resultObj->errmsg : 'errcode:' . $resultObj->errcode . ' errmsg:';
                    echo $errMsg;
                    echo $openid;
                    echo self::$appid;
                    echo self::$appsecret;
                    return false;
                }
            }
            return false;
        }
    }

    public static function sendMass($data)
    {
//        self::getToken();
//        $redis = Redis::inst('student', '_');
        $accessToken = serv::sole('')->getAccessToken('mp');
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$accessToken;
        $result = self::https_request($url, json_encode($data));
        return $result;
    }

    public static function template($channel = 'mail_success', $open_id, $data)
    {

        $method = 'template_' . $channel;
        // 监控消息提醒
        $template = array(
            "touser" => $open_id,
            "template_id" => self::$_template_data [$channel]['id'],
            "url" => $data['url'] ?? '',
            "topcolor" => "#FF0000",
            "data" => self::$method ($data)
        );
        return json_encode($template);
    }

    public static function template_deal_result($data)
    {
        return array (
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['reason'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['result'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }


    public static function template_enroll_success($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['title'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['time'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }

    public static function template_income_settlement_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['keyword3'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }


    public static function template_change_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }

    public static function template_review_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['project'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['result'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['time'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }

    public static function template_refund_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['status'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['lesson'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['money'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_lesson_upcome_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['title'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['time'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_enchashment_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "money" => array(
                "value" => $data['money'] ?? "",
                "color" => ""
            ),
            "timet" => array(
                "value" => $data['timet'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_feedback_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['ticket'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['reply'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_cash_back_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['keyword3'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_listen_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['keyword3'] ?? "",
                "color" => ""
            ),
            "keyword4" => array(
                "value" => $data['keyword4'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_lesson_progress($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['keyword3'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),
        );
    }

    public static function template_call_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "keyword3" => array(
                "value" => $data['keyword3'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }


    public static function template_to_user_notice($data)
    {
        return array(
            "first" => array(
                "value" => $data['first'] ??  "",
                "color" => ""
            ),
            "keyword1" => array(
                "value" => $data['keyword1'] ?? "",
                "color" => ""
            ),
            "keyword2" => array(
                "value" => $data['keyword2'] ?? "",
                "color" => ""
            ),
            "remark" => array(
                "value" => $data['remark'] ?? "",
                "color" => ""
            ),

        );
    }

    public static function template_commission_notice($data)
    {
        return [
            'first' => [
                'value' => $data['first'] ?? '',
                'color' => ''
            ],
            'keyword1' => [
                'value' => $data['amount'] ?? '',
                'color' => ''
            ],
            'keyword2' => [
                'value' => $data['time'] ?? '',
                'color' => ''
            ],
            'remark' => [
                'value' => $data['remark'] ?? '',
                'color' => ''
            ]
        ];
    }

    /**
     * 获取Access Token
     */
    public static function getToken()
    {
//        return serv::sole('')->getAccessToken('mp');
        /*
        $redis = Redis::inst('student', '_');
        if (empty ($redis->get('access_token'))) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::$appid . "&secret=" . self::$appsecret;
            $res = self::https_request($url);
            //	echo '<pre>';var_dump(self::$appid);exit;
            $result = json_decode($res, true);
            // save to Redis
            $redis->set('access_token', $result ["access_token"]);
            $redis->expire('access_token', 7199); // 比7200小一秒
            self::$access_token = $result ["access_token"];
        }
        */
    }

    public static function getXMLContent()
    {
        $postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
        /*
         * $postStr = "<xml>
         * <ToUserName><![CDATA[toUser]]></ToUserName>
         * <FromUserName><![CDATA[123]]></FromUserName>
         * <CreateTime>1348831860</CreateTime>
         * <MsgType><![CDATA[lcation]]></MsgType>
         * <Content><![CDATA[3]]></Content>
         * <Location_X>31.23</Location_X>
         * <Location_Y>121.47</Location_Y>
         * <MsgId>1234567890123456</MsgId>
         * </xml>";
         */
        $postArr = array();
        if (!empty ($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postArr ['from_user_name'] = ( string )$postObj->FromUserName;
            $postArr ['to_user_name'] = ( string )$postObj->ToUserName;
            $postArr ['location_X'] = ( string )$postObj->Location_X;
            $postArr ['location_Y'] = ( string )$postObj->Location_Y;
            $postArr ['msg_type'] = ( string )$postObj->MsgType;
            $postArr ['keyword'] = ( string )trim($postObj->Content);
            $postArr ['event'] = ( string )$postObj->Event;
            $postArr ['event_key'] = ( string )$postObj->EventKey;
            $postArr ['pic_url'] = ( string )$postObj->PicUrl;
        }

        return $postArr;
    }

    public function send_template_message($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . self::$access_token;
        $res = self::https_request($url, $data);
        return json_decode($res, true);
    }

    // https请求（支持GET和POST）
    protected static function https_request($url, $data = null)
    {
        /*
        $fp = fsockopen('api.weixin.qq.com', 80, $error, $errstr, 1);
        $accessToken = serv::sole($this->platform)->getAccessToken('mp');
        $http = "POST /cgi-bin/message/template/send?access_token={$accessToken} HTTP/1.1\r\nHost: api.weixin.qq.com\r\nContent-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($params) . "\r\nConnection:close\r\n\r\n$params\r\n\r\n";
        fwrite($fp, $http);
        fclose($fp);
        */
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty ($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    /**
     * 客服消息-发送文字
     * @param $content string 发送内容
     * @param $openid
     * @return bool
     */
    public static function sendText($content, $openid)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $url = sprintf(self::$custom_url, serv::sole('')->getAccessToken('mp'));
        $data = array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array(
                'content' => $content
            )
        );
        $result = self::https_request($url, json_encode($data, JSON_UNESCAPED_UNICODE));
        $resultObj = json_decode($result);
        if ($resultObj->errcode == 0) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 客服消息-发送图片
     * @param $media_id string 媒体文件id
     * @param $openid
     * @return bool
     */
    public static function sendPic($media_id, $openid)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $url = sprintf(self::$custom_url, serv::sole('')->getAccessToken('mp'));
        $data = array(
            'touser' => $openid,
            'msgtype' => 'image',
            'image' => array(
                'media_id' => $media_id
            )
        );
        $result = self::https_request($url, json_encode($data, JSON_UNESCAPED_UNICODE));
        $resultObj = json_decode($result);
        if ($resultObj->errcode == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 上传临时素材-图片
     * @param $pic_url
     * @return mixed
     */
    public static function uploadPic($pic_url)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . serv::sole('')->getAccessToken('mp') . '&type=image';
        $file = new \CURLFile(realpath($pic_url));
        $files = array('media' => $file);
        $result = self::https_request($url, $files);
        return $result;
    }

    /**
     * 客服消息-发送图文
     * @param $article
     * @param $openid
     * @return bool
     */
    public static function sendNews($article, $openid)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $url = sprintf(self::$custom_url, serv::sole('')->getAccessToken('mp'));
        $data = array(
            'touser' => $openid,
            'msgtype' => 'news',
            'news' => array(
                'articles' =>
                    array($article)),
        );

        $result = self::https_request($url, json_encode($data, JSON_UNESCAPED_UNICODE));
        $resultObj = json_decode($result);
        if ($resultObj->errcode == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function listMaterial($type, $offset, $count)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.serv::sole('')->getAccessToken('mp');
        $data = [
            'type' => $type,
            'offset' => $offset,
            'count' => $count,
        ];
        $res = self::https_request($url, json_encode($data));
        return $res;
    }

    public static function dataCube($action, $data)
    {
//        self::getToken();
//        $redis = Redis::inst('student');
        $token = serv::sole('')->getAccessToken('mp');
        $url = "https://api.weixin.qq.com/datacube/$action?access_token=$token";
        foreach ($data as $k => $v) {
            $url .= "&$k=$v";
        }
        $res = self::https_request($url, json_encode($data));
        return $res;
    }

}
