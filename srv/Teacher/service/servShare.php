<?php


namespace Teacher;


use Core\library\Tool;
use Core\unitInstance;
use _\servQiniu;

class servShare extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function getRoundAvatar($uid)
    {
        $usn = servUser::sole($this->platform)->uid2usn($uid);

        $key = uniqid('card/tmp/');
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1]);
        $avatarUrl = \view::upload("user/$usn/avatar", Tool::timeEncode(date('Y-m-d H:i:s')));
        $key = servQiniu::inst()->getRoundAvatar($avatarUrl . '&imageView2/1/w/200/h/200', $key, $token);
        if ($key) {
            return \view::upload($key, Tool::timeEncode(date('Y-m-d H:i:s')));
        }
        return false;
    }

    public function getQrcode($url)
    {
        $key = uniqid('card/tmp/qrcode/') . '.png';
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1]);
        $logoUrl = \view::upload('logo/Square_518518@2x.png');
        $key = servQiniu::inst()->putQrcode($key, $url, $logoUrl, $token);
        if ($key) {
            return \view::upload($key, Tool::timeEncode(date('Y-m-d H:i:s')));
        }
        return false;

    }


}
