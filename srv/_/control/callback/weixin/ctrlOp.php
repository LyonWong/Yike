<?php
/**
 * 微信开放平台
 */

namespace _\callback\weixin;

use _\weixin\servOp;
use _\servUser;
use _\servSession;
use Core\unitDoAction;
use Core\unitHttp;

class ctrlOp extends ctrl_
{

    protected $platform = null;
    protected $flag;

    use unitDoAction;
    use unitHttp;

    public function runBefore()
    {
        return true;
    }

    public function __construct()
    {
        parent::__construct();
        $this->flag = base_convert(crc32($_SERVER['HTTP_USER_AGENT']), 10, 32);
    }

    public function _DO_webLogin($index = 'index')
    {
        $code = \input::get('code')->value();
        $callbackURI = \input::get('callbackURI')->value();
        $callbackURI = $callbackURI ? urldecode($callbackURI) : '/';
        parse_str(parse_url($callbackURI)['query'] ?? '', $query);
        $originKey = $query['origin'] ?? '_'; //缺省为自然来路
        if ($uid = servOp::sole($this->platform)->weixinLogin($code, $originKey)) {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $token = servSession::sole($this->platform, $usn)->start($this->flag);
            $this->httpLocation(\config::load('weixin', 'callback', $index . 'Login') . '?cookieToken=' . $token . '&callbackURI=' . urlencode($callbackURI));

        }

    }


}