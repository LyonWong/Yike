<?php


namespace _;

use Core\library\Http;
use Core\unitAPI;
use Core\unitDoAction;

class ctrlAd extends ctrl_
{

    use unitAPI;
    use unitDoAction;

    public function _GET_lesson()
    {
        $sn = $this->apiRequest('sn');
        $origin = $this->apiGET('origin', '_');
        $ad = $this->apiGET('ad');
        $domain = \config::load('boot', 'public', 'domain', null, 'Student');
        $link = Http::makeURL("$_SERVER[REQUEST_SCHEME]://$domain/share", ['lesson_sn' => $sn, 'origin' => $origin]);
        $prefix = config::load('boot', 'public', 'upload', '/assets/upload');
        $imgUrl = "$prefix/lesson/$sn/ad-$ad";
        \view::tpl('ad/lesson')
            ->with('link', $link, false)
            ->with('imgUrl', $imgUrl);
    }

}