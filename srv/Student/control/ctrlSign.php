<?php


namespace Student;


use _\weixin\servMip;
use Core\unitAPI;
use Core\unitDoAction;
use Core\unitHttp;
use Core\unitAjax;
use Core\library\Tool;
use \Student\sign\servWeixin as serWeixin;
use \Student\sign\servAccount as servAccount;
use Student\sign\servWeixin;
use Core\library\QRcode;


class ctrlSign extends ctrlSess
{
    use unitDoAction;
    use unitHttp;
    use unitAPI;
    use unitAjax;


    public function runBefore()
    {
        return true;
    }

    public function _DO_ok()
    {
        echo 'ok';
    }

    public function _DO_in($method = 'weixin')
    {
        $callbackURI = \input::get('callbackURI')->value();
        $ip = \input::ip();
        $allowedIPs = \config::load('option', 'allowed', 'register.IPs', []);
        $showCreate = Tool::IPcheck($ip, $allowedIPs);
        $wechatInfo = servWeixin::sole($this->platform)->weixinInfo($callbackURI);
        \view::tpl("/sign/in-$method")
            ->with('showCreate', $showCreate)
            ->with('callbackURI', $callbackURI)
            ->with('wechatInfo', $wechatInfo);
    }

    public function _POST_in()
    {
        $email = \input::post('account')->value();
        $password = \input::post('password')->value();
        if ($uid = servAccount::sole($this->platform)->verify($email, $password)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->setCookie('sess-student', $token);
            $this->apiSuccess();
        } else {
            $this->ajaxFailure();
        }
    }

    public function _GET_inWxa()
    {
        $code = $this->apiGET('code');
        $encryptedData = $this->apiGET('encryptedData');
        $iv = $this->apiGET('iv');
        $origin = $this->apiGET('origin', '');
        $callbackURI = $this->apiGET('callbackURI','/');
        $uid = servMip::sole($this->platform)->login($code, $encryptedData, $iv, $origin);
//        \view::tpl("/sign/in-wxa")
//            ->with('url',$callbackURI);
//        exit;
        if (is_int($uid)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
//            $domain = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
//            $url = $domain . '/?cookieToken=' . $token;
            self::setCookie(self::SESS_COOKIE, $token);
//            \view::tpl("/sign/in-wxa")
//                ->with('url',$callbackURI);
//            exit;
            header("Location: "  . $callbackURI);
            exit;
//            $this->apiSuccess($url);

        }
        $this->apiFailure(self::ERR_UNDEFINED,[$uid]);
    }

    public function _DO_weixin()
    {
        $code = \input::get('code')->value();
        serWeixin::sole($this->platform)->weixinLogin($code);

    }

    public function _DO_out()
    {
        list($usn) = explode('-', $_COOKIE[ctrlSess::SESS_COOKIE] ?? '-');
        servSession::sole($this->platform, $usn)->close();
        $this->setCookie(ctrlSess::SESS_COOKIE, null, 0);
        $this->httpLocation('/');
    }

    public function _GET_qrCode()
    {
        $url = $this->apiGET('url');
        $url = base64_decode($url);
        Header("Content-type: image/png");
        QRcode::png($url, false, QR_ECLEVEL_H, 7,1);

    }


}