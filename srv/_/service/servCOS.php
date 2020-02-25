<?php


namespace _;

require_once PATH_ROOT.'/library/cos-php-sdk-v4/include.php';

use Core\unitInstance;
use qcloudcos\Auth;
use qcloudcos\Conf;
use qcloudcos\Cosapi;

class servCOS extends serv_
{
    use unitInstance;

    protected $BUCKET;

    protected $EXPIRE;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);

        Conf::$APP_ID = \config::load('tencent', 'cos', 'AppId');
        Conf::$SECRET_ID = \config::load('tencent', 'cos', 'SecretId');
        Conf::$SECRET_KEY = \config::load('tencent', 'cos', 'SecretKey');

        Cosapi::setRegion(\config::load('tencent', 'cos', 'Region', 'gz'));

        $this->BUCKET = \config::load('tencent', 'cos', 'Bucket');
        $this->EXPIRE = \config::load('tencent', 'cos', 'Expire', 300);
    }

    /**
     * @param $filePath string 待操作文件路径
     * @param null|int $expire 过期时间，秒
     * @return int|string
     */
    public function signature($filePath, $expire=null)
    {
        if ($expire) {
            $expiration = time() + $expire;
            $sign = Auth::createReusableSignature($expiration, $this->BUCKET, $filePath);
        } else {
            $sign = Auth::createNonreusableSignature($this->BUCKET, $filePath);
        }
        return $sign;
    }

    public function upload($srcPath, $dstPath)
    {
        return Cosapi::upload($this->BUCKET, $srcPath, $dstPath);
    }


}