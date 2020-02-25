<?php


namespace _\callback\weixin;

include_once PATH_ROOT.'/library/WXwork/WXBizMsgCrypt.php';

use _\config;

class ctrlWork extends ctrl_
{
    public function _DO_()
    {
        $this->parse();
    }

    public function _DO_data()
    {
        echo $this->parse();
    }

    public function _DO_command()
    {
        echo $this->parse();
    }

    public function _POST_command()
    {
        $msgSignature = \input::get('msg_signature')->value();
        $timestamp = \input::get('timestamp')->value();
        $nonce = \input::get('nonce')->value();
        $postData = \phpStream::input();
        $conf = config::load('weixin', 'workwxa');
        $wxcrpyt = new \WXBizMsgCrypt($conf['Token'], $conf['EncodingAESKey'], $conf['SuiteID']);
        $error = $wxcrpyt->DecryptMsg($msgSignature, $timestamp, $nonce, $postData, $res);
        if ($error == 0) {
            \output::debug('wxwork', $res, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
            echo 'success';
        } else {
            \output::debug('wxwork', $error, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
            echo 'failed';
        }
    }

    public function parse()
    {
        $msgSignature = \input::get('msg_signature')->value();
        $timestamp = \input::get('timestamp')->value();
        $nonce = \input::get('nonce')->value();
        $echostr = \input::get('echostr')->value();

        $conf = config::load('weixin', 'workwxa');

        $wxcrpyt = new \WXBizMsgCrypt($conf['Token'], $conf['EncodingAESKey'], $conf['CorpID']);
        $res = '';
        $error = $wxcrpyt->VerifyURL($msgSignature, $timestamp, $nonce, $echostr, $res);
        if ($error == 0) {
            return $res;
        } else {
            return false;
        }
    }
}