<?php

namespace Teacher;

use _\servLessonSeries;
use _\servOrigin;
use _\servQiniu;
use Core\unitAPI;


class ctrlShare extends ctrlSess
{
    use unitAPI;

    public function _GET_invite()
    {

        $lessonSn = $this->apiGET('lesson_sn');
        $lessonDetail = servLesson::sole($this->platform)->detail($lessonSn);
        if ($lessonDetail) {
//            $cardUrl = \view::upload("share/card");
            $avatarRoundUrl = servShare::sole($this->platform)->getRoundAvatar($this->uid);
            $domain = \config::load('boot', 'public', 'domain', null, '_');
            $originKey = "teacher-$this->usn";
            $originName = "讲师传播-{$lessonDetail['teacher']['name']}";
            servOrigin::sole($this->platform)->checkIn($originKey, $originName);
            $shareUrl = "$_SERVER[REQUEST_SCHEME]://$domain/lesson/detail?sn=$lessonSn&origin=$originKey";
            $qrcodeUrl = servShare::sole($this->platform)->getQrcode($shareUrl);
            if (!$qrcodeUrl || !$avatarRoundUrl) {
                $this->apiFailure(self::ERR_UNDEFINED);
            }
//            $cardImgUrl = servQiniu::inst()->waterImgUrl(
//                $cardUrl,
//                $avatarRoundUrl,
//                $qrcodeUrl,
//                $lessonDetail['teacher']['name'],
//                $lessonDetail['title'],
//                $lessonDetail['brief'],
//                $lessonDetail['plan']['dtm_start']);
            $this->apiSuccess(['qrcode' => $qrcodeUrl, 'share_url' => $shareUrl]);
        }
        $this->apiFailure(self::ERR_UNDEFINED);

    }

    public function _GET_series()
    {
        $seriesSn = $this->apiGET('series_sn');
        $seriesDetail = servLessonSeries::sole($this->platform)->detail($seriesSn);
        if($seriesDetail) {
            $originKey = "teacher-$this->usn";
            $domain = \config::load('boot', 'public', 'domain', null, '_');
            $shareUrl = "$_SERVER[REQUEST_SCHEME]://$domain/lesson/series?sn=$seriesSn&origin=$originKey";
            $qrcodeUrl = servShare::sole($this->platform)->getQrcode($shareUrl);
            $this->apiSuccess(['share_url'=>$shareUrl, 'qrcode' => $qrcodeUrl]);
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

}