<?php

namespace Admin\lesson;

use _\servDWZ;
use _\servOrigin;
use _\servQiniu;
use Admin\servLesson;
use Admin\servUser;
use Core\unitAPI;


class ctrlShare extends ctrl_
{
    protected $scopeKey = 'lesson-share';

    use unitAPI;

    public function _DO_()
    {
        $lessonSn = \input::get('lesson_sn')->value();
        $data = servLesson::sole($this->platform)->detail($lessonSn);
        \view::tpl('page', [
            'page' => 'lesson/share',
            'step_map' => servLesson::STEP_MAP,
        ])->with('data', $data);
    }

    public function _POST_invite()
    {

        $lessonSn = $this->apiPOST('lesson_sn');
        $originKey = $this->apiPOST('origin_key');
        $originName = $this->apiPOST('origin_name');
        $ad = $this->apiPOST('ad', '');
        $lessonDetail = servLesson::sole($this->platform)->detail($lessonSn);
        if ($lessonDetail) {
            $cardUrl = \view::upload("share/card");
            $tuid = servUser::sole($this->platform)->usn2uid($lessonDetail['teacher']['sn']);
            $avatarRoundUrl = \Teacher\servShare::sole($this->platform)->getRoundAvatar($tuid);
            $domain = \config::load('boot', 'public', 'domain', null, 'Student');
            servOrigin::sole($this->platform)->checkIn($originKey, $originName);
            $shareUrl = "$_SERVER[REQUEST_SCHEME]://$domain/share?lesson_sn=" . $lessonSn . "&origin=" . $originKey;
            if ($ad) {
                $shareUrl .= '&ad=' . $ad;
            }
            $shortUrl = '';
            $ret = servDWZ::sole($this->platform)->convert2ShortUrl($shareUrl);
            if (isset($ret['err']) && $ret['err'] == "") {
                $shortUrl = $ret['url'];
                $qrcodeUrl = \Teacher\servShare::sole($this->platform)->getQrcode($shortUrl);
            } else {
                $qrcodeUrl = \Teacher\servShare::sole($this->platform)->getQrcode($shareUrl);
            }
            if (!$qrcodeUrl || !$avatarRoundUrl) {
                $this->apiFailure(self::ERR_UNDEFINED);
            }


            $cardImgUrl = servQiniu::inst()->waterImgUrl(
                $cardUrl,
                $avatarRoundUrl,
                $qrcodeUrl,
                $lessonDetail['teacher']['name'],
                $lessonDetail['title'],
                $lessonDetail['brief'],
                $lessonDetail['plan']['dtm_start']);
            $this->apiSuccess(['card' => $cardImgUrl, 'qrcode' => $qrcodeUrl, 'share_url' => $shareUrl, 'short_url' => $shortUrl]);
        }
        $this->apiFailure(self::ERR_UNDEFINED);

    }

}