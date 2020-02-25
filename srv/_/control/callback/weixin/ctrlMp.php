<?php
/**
 * 微信公众平台
 */

namespace _\callback\weixin;

use _\dataUser;
use _\servMpMsg;
use _\sign\servWeixin;
use _\weixin\servMp;
use _\servSession;
use _\servUser;
use Core\unitDoAction;
use Core\library\Payinit;
use Core\unitHttp;

class ctrlMp extends ctrl_
{
    protected $platform = null;
    protected $flag;
    private $token;

    use unitDoAction;
    use unitHttp;

    public function __construct()
    {
        parent::__construct();
        $this->flag = base_convert(crc32($_SERVER['HTTP_USER_AGENT']), 10, 32);
    }

    /**
     * 响应微信发送的Token验证
     * @return string
     */
    public function _DO_()
    {
        // 有echostr参数表示要验证
        if (isset ($_GET ['echostr'])) {
            $this->token = "weixin";
            $this->valid();
            exit;
        } else {
            // 无echostr参数表示要进行别的操作
            $this->responseMsg();
            exit;
        }
    }

    /**
     * 微信支付回调*
     */
    public function _DO_notify()
    {
        $XML = file_get_contents('php://input');
        $Pay = Payinit::weixin('weixin');
        $Pay->_params = $XML;//微信回调提交过来的xml
        if (empty($Pay->_params) || !$Pay->verifyNotify()) {
            echo $Pay->getFailXml();
        } elseif ($Pay->_params['return_code'] == 'SUCCESS' && $Pay->_params['result_code'] == 'SUCCESS') {
            //处理业务....
            $Pay->_params = $Pay->xmlToArray($XML);
            $transaction_id = $Pay->_params['transaction_id'];   //微信支付订单号
            $out_trade_no = $Pay->_params['out_trade_no'];   //自己系统订单号
            $paid_amount = $Pay->_params['total_fee'];  //支付金额
            $ret = servMp::sole($this->platform)->doRecharge($out_trade_no, $transaction_id, $paid_amount);
            if ($ret) {
                echo $Pay->getSucessXml();
            } else {
                echo $Pay->getFailXml();
            }
        } else {

            echo $Pay->getFailXml();
        }

    }

    private function valid()
    {
        $echoStr = \input::get('echostr')->value();
        if ($this->checkSignature()) {
            ob_clean();
            echo $echoStr;
            exit ();
        }
    }

    private function checkSignature()
    {

        $signature = \input::get('signature')->value();
        $timestamp = \input::get('timestamp')->value();
        $nonce = \input::get('nonce')->value();

        $token = $this->token;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 事件推送接收
     */
    public function responseMsg()
    {
        $XML = file_get_contents('php://input');
        $ret = '';
        \output::debug('mpmsg', $XML, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        if (!empty($XML)) {
            $obj = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (isset($obj->Event)) {
                switch ($obj->Event) {
                    case 'subscribe':
                        $ret = servMp::sole($this->platform)->subscribe($obj->FromUserName, $obj->ToUserName);
                        break;
                    case 'unsubscribe':
                        servMp::sole($this->platform)->unSubscribe($obj->FromUserName);
                        break;
                    default:
                        break;
                }
            } elseif (isset($obj->MsgType) && $obj->MsgType == 'text') {
                $ret = servMp::sole($this->platform)->dealText($obj->FromUserName, $obj->ToUserName, $obj->Content);

                if (preg_match('/^抬头([\s\S]*)税号([\s\S]*)形式([\s\S]*)地址([\s\S]*)$/u', $obj->Content)) {
                    file_put_contents('/tmp/invoice.log', $obj->Content . PHP_EOL . $obj->FromUserName . PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
                }

            }

        }
        echo $ret;
        exit;

    }

    public function _DO_login($scope='index')
    {
        $code = \input::get('code')->value();
        $callbackURI = \input::get('callbackURI')->value();
        $callbackURI = $callbackURI ? urldecode($callbackURI) : '/';
        parse_str(parse_url($callbackURI)['query'] ?? '', $query);
        $originKey = $query['origin'] ?? '_'; //缺省为自然来路
        if ($uid = servWeixin::sole($this->platform)->wxm($code, $originKey)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->httpLocation(\config::load('weixin', 'callback', $scope.'Login') . '?cookieToken=' . $token . '&callbackURI=' . urlencode($callbackURI));
        }
        /*
        if ($uid = servMp::sole($this->platform)->weixinLogin($code, $originKey)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->httpLocation(\config::load('weixin', 'callback', 'studentLogin') . '?cookieToken=' . $token . '&callbackURI=' . urlencode($callbackURI));
        }
        */
    }

//    public function _DO_createMenu()
//    {
//        $ret = servMp::sole($this->platform)->createMenu();
//        echo '<pre>';
//        print_r($ret);
//        exit;
//    }


}