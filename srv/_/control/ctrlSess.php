<?php


namespace _;

use _\sign\servWeixin;
use Core\library\Language;
use Core\library\Tool;
use Core\unitAPI;
use Core\unitDoAction;
use Core\unitHttp;

class ctrlSess extends ctrl_
{
    use unitDoAction;
    use unitHttp;
    use unitAPI;

    const SESS_ROLES = ['admin', 'teacher', 'student'];

    const SESS_COOKIE = COOKIE_SESSION;

    const ERR_ILLEGAL_SESSION = ['0.1', 'illegal session'];
    const ERR_UNABLE_TO_EXECUTE = ['0.2', 'unable to execute'];
    const ERR_UNDEFINED = ['-1', '%s'];

    protected $uid;

    protected $usn;

    protected $flag;

    protected $client;

    protected $scopeKey;

    protected $platform = null;

    public function __construct()
    {
        parent::__construct();
        $this->flag = self::flag();
        $this->client = servAdaptor::client($_SERVER['HTTP_USER_AGENT']??'-');
    }

    public static function flag()
    {
        // 消除微信客户端自增ID
        $ua = preg_replace('#MicroMessenger/([.\d]+)\(0x27000031\)#', 'MicroMessenger/${1}', $_SERVER['HTTP_USER_AGENT'] ?? '');
        return base_convert(crc32($ua), 10, 32);
    }

    public function runBefore()
    {
        $pres = parent::runBefore();

        $sess = self::checkSess();
        if (!$this->uid = servSession::sole($this->platform, $sess['usn'])->check2uid($this->flag, $sess['token'])) {

            if ($this->_EXT_ == 'api') {
                $this->apiFailure(self::ERR_ILLEGAL_SESSION);
            }
            /*
            elseif ($this->client == servAdaptor::CLIENT_WXA) {
                header("Location: /sign-wxa?target=".urlencode($this->_URI_));
            }
            */
            elseif ($this->isWeixin() || Tool::isMobileRequest()) {
               servWeixin::sole($this->platform)->weixinLogin(urlencode($this->CALLBACK_URI ?? $this->_URI_));
            }
            else {
                header("Location: /sign-in?callbackURI=" . urlencode($this->CALLBACK_URI ?? $this->_URI_));
            }
            exit;
        }
        $this->usn = $sess['usn'];

        if ($this->_EXT_ == 'csv') {
//            header('Content-Type: application/download');
//            header("Content-Type: text/csv");
//            header("Content-Disposition:attachment;filename=foo.csv");
//            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
//            header('Expires:0');
//            header('Pragma:public');
        }
        servSession::$lang = \input::cookie('lang', Language::detect())->value();

        return $pres;
    }

    public function _DO_check()
    {
        if (self::checkSess()) {
            $this->apiSuccess($this->usn);
        } else {
            $this->apiFailure(self::ERR_ILLEGAL_SESSION);
        }
    }

    public function _POST_cipher()
    {
        $code = \input::ip();
        $cipher = servSession::sole($this->platform, $this->usn)->cipher($code);
        $this->apiSuccess($cipher);
    }

    public function _DO_link()
    {
        $path = \input::get('path', '/')->value();
        $hash = \input::get('hash')->value();
        $this->httpLocation("$path#$hash");
    }

    public static function setCookie($name, $value, $setTime = null, $path = '/', $domain = null)
    {
//        $expire = $setTime ? strtotime($setTime) : null;
        $expire = strtotime($setTime ?: "+15 days");
        $domain = $domain ?: config::load('boot', 'public', 'domain');
        setcookie($name, $value, $expire, $path, $domain, null, true);
        setcookie($name, '', -1, $path, "", null, true);
    }

    public static function checkSess()
    {
        $sess = $_SERVER['HTTP_X_SESS'] ?? $_COOKIE[self::SESS_COOKIE] ?? '-';
        if ($sess) {
            list($usn, $token) = explode('-', $sess);
            return [
                'usn' => $usn,
                'token' => $token,
            ];
        } else {
            return false;
        }
    }

    protected function apiFailure($error, array $args = [], $data = null, $halt = true)
    {
        $msg = Language::sole(servSession::$lang, ['API_ERR'])->refer($error[1]);
        $this->apiResponse($error[0], sprintf($msg, ...$args), $data ?? $args, $halt);
    }


    /* *
   * 检查是否是微信浏览器登陆
   * @return boolean
   */
    protected function isWeixin()
    {
        $_SERVER ['HTTP_USER_AGENT'] = isset($_SERVER ['HTTP_USER_AGENT']) ? $_SERVER ['HTTP_USER_AGENT'] : '';
        if (preg_match('/MicroMessenger/i', $_SERVER ['HTTP_USER_AGENT'])) {
            return true;
        }
        return false;
    }

}