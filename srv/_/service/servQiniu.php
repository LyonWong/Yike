<?php


namespace _;

use Core\library\Http;
use Core\library\QRcode;
use Core\library\Tool;
use Core\unitInstance;
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\ImageUrlBuilder;

require_once PATH_ROOT . '/library/qiniu-php-sdk-7.1.3/autoload.php';


class servQiniu
{
    use unitInstance;

    protected $BUCKET;

    protected $SOURCE;

    protected $auth;

    const SIZE_MB = 1024 * 1024;

    /**
     * @return self
     */
    public static function inst()
    {
        return self::_singleton();
    }

    public function __construct()
    {
        $this->BUCKET = config::load('qiniu', 'os', 'Bucket', '', '_');
        $this->SOURCE = config::load('qiniu', 'os', 'Source', '', '_');
        $accessKey = config::load('qiniu', 'os', 'AccessKey', '', '_');
        $secretKey = config::load('qiniu', 'os', 'SecretKey', '', '_');
        $this->auth = new Auth($accessKey, $secretKey);
    }

    public function BUCKET()
    {
        return $this->BUCKET;
    }

    public function getUploadToken($key = null, $policy = null)
    {
        return $this->auth->uploadToken($this->BUCKET, $key, 3600, $policy);
    }

    public function putFile($dstPath, $srcPath, $token = null)
    {
        $upload = new UploadManager();
        if ($token == null) {
            $token = $this->getUploadToken($dstPath);
        }
        return $upload->putFile($token, $dstPath, $srcPath);
    }

    public function persist($name, $fops)
    {
        $pipline = config::load('qiniu', 'dora', 'mps');
        $persist = new PersistentFop($this->auth, $this->BUCKET, $pipline);
        return $persist->execute($name, $fops);
    }

    public function persistStatus($persistsId, $try = 10)
    {
        do {
            $res = Http::inst()->get("http://api.qiniu.com/status/get/prefop", ['id' => $persistsId]);
            $res = json_decode($res);
            switch ($res->code) {
                case 0: //处理成功
                    return true;
                case 1: //等待处理
                case 2: //正在处理
                    sleep(1);
                    break;
                case 3: //处理失败
                case 4: //通知提交失败
                    return false;
            }
        } while (--$try);
    }

    public function put($name, $file, $token = null)
    {
        $upload = new UploadManager();
        if ($token == null) {
            $token = $this->getUploadToken($name);
        }
        return $upload->put($token, $name, $file);
    }

    public function fetch($URL, $name)
    {
        $bucket = new BucketManager($this->auth);
        return $bucket->fetch($URL, $this->BUCKET, $name);
    }

    public function move($fromName, $toName)
    {
        $bucket = new BucketManager($this->auth);
        return $bucket->move($this->BUCKET, $fromName, $this->BUCKET, $toName);
    }

    public function delete($name)
    {
        $bucket = new BucketManager($this->auth);
        return $bucket->delete($this->BUCKET, $name);
    }

    public function deleteAfterDays($name, $deleteAfterDays)
    {
        $bucket = new BucketManager($this->auth);
        return $bucket->deleteAfterDays($this->BUCKET, $name, $deleteAfterDays);
    }

    public function stat($name)
    {
        $bucket = new BucketManager($this->auth);
        return $bucket->stat($this->BUCKET, $name);
    }

    public function waterImgUrl($sourceUrl, $avatarUrl, $codeUrl, $name, $title, $brief, $dtmStart)
    {
        $imgUrl = new ImageUrlBuilder();
        $briefs = explode("\n", $brief);
        $briefUrl = '';
        if (count($briefs) > 1) {
            if (mb_strlen($briefs[0]) <= 20) {
                $handleOne = $briefs[0];
                $handleTwo = mb_substr($briefs[1], 0, 20);
            } else {
                $handleOne = mb_substr($briefs[0], 0, 20);
                $handleTwo = mb_substr($briefs[0], 20, 20);
            }

        } else {
            $handleOne = mb_substr($briefs[0], 0, 20);;
            $handleTwo = mb_substr($briefs[0], 20, 20);
        }
        $briefUrl .= $imgUrl->waterText(
            $handleOne,
            '微软雅黑',
            530,
            '#AAAAAA',
            '',
            'North',
            0,
            '460'
        );
        if (!empty($handleTwo)) {
            $briefUrl .= $imgUrl->waterText(
                $handleTwo,
                '微软雅黑',
                530,
                '#AAAAAA',
                '',
                'North',
                0,
                '490'
            );
        }
        $url = $imgUrl->water(ImageUrlBuilder::WATER_IMG_TEXT, $sourceUrl) . $imgUrl->waterText(
                $name,
                '微软雅黑',
                580,
                '',
                '',
                'North',
                0,
                '236'
            ) . $imgUrl->waterText(
                $title,
                '微软雅黑',
                600,
                '',
                '',
                'North',
                0,
                '400'
            ) . $briefUrl
            . $imgUrl->waterText(
                $dtmStart,
                '微软雅黑',
                600,
                '#3C4A55',
                '',
                'North',
                0,
                '644'
            ) . $imgUrl->waterImg(
                $avatarUrl . '&imageView2/1/w/172/h/172',
                100,
                'North',
                0,
                '41',
                '1'
            ) . $imgUrl->waterImg(
                $codeUrl . '&imageView2/1/w/240/h/240',
                100,
                'North',
                0,
                '720',
                '1'
            );
        return $url;
    }

    public function promoteImgUrl($sourceUrl, $codeUrl, $name)
    {
        $imgUrl = new ImageUrlBuilder();
        $url = $imgUrl->water(ImageUrlBuilder::WATER_IMG_TEXT, $sourceUrl)
            . $imgUrl->waterText(
                '来自 ' . $name,
                '微软雅黑',
                580,
                '#999999',
                '',
                'North',
                0,
                '156'
            ) . $imgUrl->waterImg(
                $codeUrl . '&imageView2/1/w/150/h/150',
                100,
                'North',
                0,
                '690',
                '1'
            );
        return $url;

    }

    function putQrcode($key, $url, $logoUrl = null, $token = null)
    {
        ob_start();
        QRcode::png($url, false, QR_ECLEVEL_H, 7);
        $data = ob_get_contents();
        ob_end_clean();
        if ($logoUrl != null) {
            $logo = Http::inst()->get($logoUrl);
            $logo = imagecreatefromstring($logo);
            $QR = imagecreatefromstring($data);
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            ob_start();
            imagepng($QR);
            $data = ob_get_contents();
            ob_end_clean();
        }

        $ret = $this->put($key, $data, $token);
        if (isset($ret[0]['key']) && $ret[0]['key'] == $key) {
            return $key;
        }
        return false;
    }

    function getQrcode($url, $logoUrl)
    {
        ob_start();
        QRcode::png($url, false, QR_ECLEVEL_H, 7);
        $data = ob_get_contents();
        ob_end_clean();
        if ($logoUrl != null) {
            $logo = Http::inst()->get($logoUrl);
            $logo = imagecreatefromstring($logo);
            $QR = imagecreatefromstring($data);
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
//            ob_start();
            imagepng($QR);
//            $data = ob_get_contents();
//            ob_end_clean();
        }
//        return $data;
    }

    function getRoundAvatar($url, $key, $token = null)
    {
        $w = 200;
        $h = 200; // original size
        $original_path = $url;
        $src = imagecreatefromstring(Http::inst()->get($original_path));
        $newpic = imagecreatetruecolor($w, $h);
        imagealphablending($newpic, false);
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
        $r = $w / 2;
        for ($x = 0; $x < $w; $x++)
            for ($y = 0; $y < $h; $y++) {
                $c = @imagecolorat($src, $x, $y);
                $_x = $x - $w / 2;
                $_y = $y - $h / 2;
                if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
                    imagesetpixel($newpic, $x, $y, $c);
                } else {
                    imagesetpixel($newpic, $x, $y, $transparent);
                }
            }
        imagesavealpha($newpic, true);
        imagepng($newpic, '/tmp/' . base64_decode($key));
        $ret = $this->putFile($key, '/tmp/' . base64_decode($key), $token);
        imagedestroy($newpic);
        imagedestroy($src);
        unlink('/tmp/' . base64_decode($key));
        if (isset($ret[0]['key']) && $ret[0]['key'] == $key) {
            return $key;
        }
        return false;
    }

    public function returnQrcode($url)
    {
        $key = uniqid('card/tmp/qrcode/') . '.png';
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1]);
        $logoUrl = \view::upload('card/logo');
        $key = servQiniu::inst()->putQrcode($key, $url, $logoUrl, $token);
        if ($key) {
            return \view::upload($key, Tool::timeEncode(date('Y-m-d H:i:s')));
        }
        return false;

    }
}