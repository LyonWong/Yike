<?php


namespace _;


use _\sign\servWeixin;
use _\weixin\servMip;
use _\weixin\servOp;
use Core\library\Tool;
use Core\unitAPI;
use Core\unitDoAction;
use Core\unitHttp;

class ctrlSign extends ctrl_
{
    use unitDoAction;
    use unitAPI;
    use unitHttp;

    const ERR_FAILED_TO_SIGN = [1, 'Failed to sign'];

    protected $platform = null;

    public function _DO_()
    {
        echo 'sign';
    }

    public function _DO_out()
    {
        ctrlSess::setCookie(ctrlSess::SESS_COOKIE, '', time()-1);
        $this->httpLocation('/');
    }

    public function _DO_in($method = 'weixin')
    {
        $callbackURI = \input::get('callbackURI')->value();
        if ($method == 'weixin' && servAdaptor::client($_SERVER['HTTP_USER_AGENT']) == servAdaptor::CLIENT_WXM) {
            servWeixin::sole($this->platform)->weixinLogin($callbackURI);
        }
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        $showCreate = Tool::IPcheck($ip, $allowedIPs);
        $wechatInfo = servOp::sole($this->platform)->weixinInfo($callbackURI);
        \view::tpl("/sign/in-$method")
            ->with('showCreate', $showCreate)
            ->with('callbackURI', $callbackURI)
            ->with('wechatInfo', $wechatInfo);
    }

    public function _POST_wxa()
    {
        $code = $this->apiPOST('code');
        $encryptedData = $this->apiPOST('encryptedData');
        $iv = $this->apiPOST('iv');
        $origin = $this->apiPOST('origin', '_');
        $uid = servWeixin::sole($this->platform)->wxa($code, $encryptedData, $iv, $origin);
        if (!$uid) {
            $this->apiFailure(self::ERR_FAILED_TO_SIGN);
        }
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        $token = servSession::sole($this->platform, $usn)->start(ctrlSess::flag());
        $data = [
            'usn' => $usn,
            'token' => $token
        ];
        $this->apiSuccess($data);
    }

    public function _GET_wxa()
    {
        $target = \input::get('target', '/')->value();
        $cipher = \input::get('cipher')->value();

        $sess = ctrlSess::checkSess();
        $flag = ctrlSess::flag();
//        \output::debug('flag', $flag, 1, 2);
        if ($sess && servSession::sole($this->platform, $sess['usn'])->check($flag, $sess['token'])) {
            $this->httpLocation($target);
            exit;
        }
        if ($cipher && $usn = servSession::verify($cipher, \input::ip())) {
            $token = servSession::sole($this->platform, $usn)->start(ctrlSess::flag());
            ctrlSess::setCookie(ctrlSess::SESS_COOKIE, $token);
            $this->httpLocation($target);
            exit;
        }
        \view::tpl('wxa', [
            'method' => 'reLaunch',
            'wxaUrl' => "/page/boot?mode=webview&target=$target"
        ]);
    }

    public function _GET__wxa()
    {
        $target = \input::get('target', '/')->value();
        $code = \input::get('code')->value();
        $iv = \input::get('iv')->value();
        $encryptedData = \input::get('encryptedData')->value();
        $origin = \input::get('origin')->value();

        $sess = ctrlSess::checkSess();
        $flag = ctrlSess::flag();
        if ($sess && servSession::sole($this->platform, $sess['usn'])->check($flag, $sess['token'])) {
            \view::tpl('sign/wxa', [
                'target' => $target
            ]);
            exit;
        }

        if ($uid = servMip::sole($this->platform)->login($code, $encryptedData, $iv, $origin)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($flag);
            ctrlSess::setCookie(ctrlSess::SESS_COOKIE,  $token);
            \view::tpl('sign/wxa', [
                'target' => $target
            ]);
        } else {
            echo "Illegal Login.";
        }
    }

}