<?php


namespace Student;


use _\dataLessonAccess;
use _\dataPromote;
use _\servCache;
use _\servOrigin;
use _\servPromote;
use _\servQiniu;
use _\servShare;
use _\servUnionOrder;
use Core\library\QRcode;
use Core\library\Tool;

class ctrlPromote extends ctrlSess
{

    const ERR_COUPON_NOT_EXISTS = ['1', '优惠卷不存在'];
    const ERR_COUPON_NOT_AVAILABLE = ['2', '优惠卷已失效'];
    const ERR_COUPON_HAS_GOT = ['3', '已经领取过了'];
    const ERROR_CREATE_UNENROLL = ['4', '未报名不能创建优惠卷'];
    const ERR_CANNOT_DRAW_SELF = ['5', '不能领取自己的优惠券'];
    const ERR_IP_NOT_ALLOWED = ['6', 'IP受限，请在指定IP范围内领取'];

    protected $CALLBACK_URI = null;

    public function runBefore()
    {
        if ($sn = \input::get('sn')->value()) {
            $attr = servPromote::sole($this->platform)->attr($sn);
            $origin = servOrigin::sole($this->platform)->id2key($attr['origin_id']);
            $this->CALLBACK_URI = "$this->_URI_&origin=$origin";
        }
        return parent::runBefore();
    }


    public function _GET_()
    {
        $targetSn = \input::get('target_sn')->value();
        //生财课程直接跳转卡片页
        if ($targetSn == 'S59ffcaacd9c4d' || $targetSn == 'L5a030660af352' || $targetSn == 'L5a02a969a1d46' || $targetSn == 'L59ffe3e32d3c3' || $targetSn == 'L5a02a65fb4995') {
            $this->httpLocation('/promote-card?target_sn=S59ffcaacd9c4d');
            exit;
        }
        $target = servPromote::sole($this->platform)->targetInfo($targetSn);
        if ($target['lesson_id']) {
            $srvLesson = servLesson::sole($this->platform);
            $enrolled = $srvLesson->isEnrolled($target['lesson_id'], $this->uid);
        } else {
            $enrolled = servUnionOrder::sole($this->platform)->isPurchased($targetSn, $this->uid);
        }
        \view::tpl('/promote/index', [
            'target_sn' => $targetSn,
            'enrolled' => $enrolled,
        ]);
    }

    public function _GET_redeem()
    {
        if ($sn = \input::get('sn')->value()) {
            if ($force = \input::get('force')->value()) {
                $attr = servPromote::sole($this->platform)->attr($sn);
                if ($attr && $attr['uid'] != $this->uid) {
                    servPromote::sole($this->platform)->draw($sn, $this->uid);
                }
                $scheme = $_SERVER['REQUEST_SCHEME'];
                $domain = \config::load('boot', 'public', 'domain', null, '_');
                if ($attr['lesson_id']) {
                    $target = servLesson::sole($this->platform)->id2info($attr['lesson_id'], ['sn', 'i_form']);
                    $origin = servOrigin::sole($this->platform)->chop(['id' => $attr['origin_id']], 2);
                    if ($origin[0]['key'] == 'home' && isset($origin[1])) {
                        $home = "home/{$origin[1]['key']}";
                    }
                    if ($target['i_form'] == dataLesson::FORM_ARTICLE) {
                        $home = $home ?? 'study';
                        $location = "$_SERVER[REQUEST_SCHEME]://$domain/$home/article?sn=$target[sn]";
                    } else if ($target['i_form'] == dataLesson::FORM_COLUMN) {
                        $home = $home ?? 'lesson';
                        $location = "$_SERVER[REQUEST_SCHEME]://$domain/$home/column?sn=$target[sn]";
                    } else {
                        $location  = "$scheme://$domain/lesson/detail?sn=$target[sn]";
                    }
                    $this->httpLocation($location);
                    exit;
                } else {
                    $target = servLessonSeries::sole($this->platform)->detail($attr['series_id'], 'id');
                    if ($target['sn'] == 'S5a15481107f2a') { // 明白的爬虫课
                        $_usn = servUser::sole($this->platform)->uid2usn($attr['uid']);
                        $this->httpLocation("$scheme://$domain/lesson/series?sn=S5b128f16ddb5a&source=old-$_usn");
                    } else {
                        $this->httpLocation("$scheme://$domain/lesson/series?sn=$target[sn]");
                    }
                    exit;
                }
            }
            $this->httpLocation("/promote-receive?sn=$sn");

        } else {
            \view::tpl('/promote/redeem');
        }
    }

    public function _GET_receive()
    {
        $sn = $this->apiGET('sn');
        $info = servPromote::sole($this->platform)->attr($sn);
        $psn = $info['promote'] ?? $sn;
        $info['discount'] /= 100;
        if ($info && $info['i_status'] == dataPromote::STATUS_AVAILABLE) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
            $domain = \config::load('boot', 'public', 'domain', null, '_');
            if ($info['lesson_id']) {
                $target = servLesson::sole($this->platform)->detail($info['lesson_id'], 'id');
//                $target['href'] = '/?#/course/detail/brief?lesson_sn=' . $target['sn'];
                if ($target['form'] == 'article') {
                    $target['href'] = "$scheme://$domain/study/article?sn=$target[sn]";
                } else {
                    $target['href'] = "$scheme://$domain/lesson/detail?sn=$target[sn]";
                }
            } else {
                $target = servLessonSeries::sole($this->platform)->detail($info['series_id'], 'id');
                $target['title'] = $target['name'];
                $target['price'] = $target['scheme']['price'];
//                $target['href'] = "/?#/course/series/detail/$target[sn]/brief";
                $target['href'] = "$scheme://$domain/lesson/series?sn=$target[sn]";
            }
            $check = servPromote::sole($this->platform)->check($this->uid, $info['lesson_id'], $info['series_id']);
            $info['user'] = \_\servUser::sole($this->platform)->uid2profile($info['uid']);

            $share = new servShare();
            $domain = \config::load('boot', 'public', 'domain', '', 'Student');
            $url = "$_SERVER[REQUEST_SCHEME]://$domain$_SERVER[REQUEST_URI]";
            $shareConfig = $share->wxGetConfig(urldecode($url));
        }
        \view::tpl("/promote/receive")
            ->with('uid', $this->uid)
            ->with('target', $target ?? false)
            ->with('info', $info)
            ->with('check', $check ?? false)
            ->with('sn', $sn)
            ->with('psn', $psn)
            ->with('shareConfig', $shareConfig ?? false)
            ->with('url', $url ?? false);
    }

    public function _POST_use()
    {
        $sn = $this->apiPOST('sn');
        $cover = $this->apiPOST('cover', false);
        $info = servPromote::sole($this->platform)->info($sn);
        if ($info) {
            if ($info['i_status'] == dataPromote::STATUS_AVAILABLE) {
                $check = servPromote::sole($this->platform)->check($this->uid, $info['lesson_id'], $info['series_id']);
                if (!$check || ($check != $sn && $cover)) {
                    $ret = servPromote::sole($this->platform)->draw($sn, $this->uid);
                    if ($ret) {
                        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
                        $lessonSn = servLesson::sole($this->platform)->id2sn($info['lesson_id']);
                        $location = "$_SERVER[REQUEST_SCHEME]://$domain/?#/course/detail/brief?lesson_sn=$lessonSn";
                        $this->apiSuccess($location);
                    }
                    $this->apiFailure(self::ERR_UNDEFINED);
                }
                $this->apiFailure(self::ERR_COUPON_HAS_GOT);

            }
            $this->apiFailure(self::ERR_COUPON_NOT_AVAILABLE);

        }
        $this->apiFailure(self::ERR_COUPON_NOT_EXISTS);

    }

    public function _POST_draw()
    {
        $sn = $this->apiPOST('sn');
        $force = $this->apiPOST('force', false); // 强制领取
        $attr = servPromote::sole($this->platform)->attr($sn);
        $psn = $sn[0] == 'P' ? $sn : $attr['promote'];

        //不存在
        if (!$attr) {
            $this->apiFailure(self::ERR_COUPON_NOT_EXISTS);
        }

        if (($IPs = $attr['extra']['allowed_IPs'] ?? null)
            && Tool::IPcheck(\input::ip(), $IPs) === false
        ) {
            $this->apiFailure(self::ERR_IP_NOT_ALLOWED);
        }

        if ($attr['uid'] == $this->uid && $attr['i_type'] != dataPromote::TYPE_HAGGLE) {
            $this->apiFailure(self::ERR_CANNOT_DRAW_SELF);
        }

        //已过期
        if ($attr['i_status'] != dataPromote::STATUS_AVAILABLE ||
            ($attr['expire'] && strtotime($attr['expire']) < time())
        ) {
            $this->apiFailure(self::ERR_COUPON_NOT_AVAILABLE);
        }

        // 检查是否已领过
        $_psn = servPromote::sole($this->platform)->check($this->uid, $attr['lesson_id'], $attr['series_id']);
        if ($_psn && $psn != $_psn && !$force) {
            $this->apiFailure(self::ERR_COUPON_HAS_GOT);
        }

        //领取失败
        if ($psn != $_psn && !servPromote::sole($this->platform)->draw($sn, $this->uid)) {
            $this->apiFailure(self::ERR_UNDEFINED, ['领取失败']);
        }
        \output::debug('promote', "$this->usn: $sn-$psn", DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);

        //领取成功，跳转到详情页
        $domain = \config::load('boot', 'public', 'domain', null, '_');
        if ($attr['lesson_id']) {
            $lessonSn = servLesson::sole($this->platform)->id2sn($attr['lesson_id']);
            $lesson = servLesson::sole($this->platform)->id2info($attr['lesson_id'], ['sn', 'i_form']);
            $origin = servOrigin::sole($this->platform)->chop(['id' => $attr['origin_id']], 2);
            if ($origin[0]['key'] == 'home' && isset($origin[1])) {
                $home = "home/{$origin[1]['key']}";
            }
            if ($lesson['i_form'] == dataLesson::FORM_ARTICLE) {
                $home = $home ?? 'study';
                $location = "$_SERVER[REQUEST_SCHEME]://$domain/$home/article?sn=$lessonSn";
            } else if ($lesson['i_form'] == dataLesson::FORM_COLUMN) {
                $home = $home ?? 'lesson';
                $location = "$_SERVER[REQUEST_SCHEME]://$domain/$home/column?sn=$lessonSn";
            }
            else {
                $location = "$_SERVER[REQUEST_SCHEME]://$domain/lesson/detail?sn=$lessonSn";
            }
        } else {
            $seriesSn = servLessonSeries::sole($this->platform)->id2sn($attr['series_id']);
            $location = "$_SERVER[REQUEST_SCHEME]://$domain/lesson/series?sn=$seriesSn";
        }
        $this->apiSuccess($location);
    }

    public function _GET_lesson()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $lesson = servLesson::sole($this->platform)->detail($lessonSn, 'sn');
        $promote = servPromote::sole($this->platform)->calc($lessonSn, 0.15, 0.15);
        $recent = servLesson::sole($this->platform)->recent($lessonSn, $this->uid, [
            dataLessonAccess::EVENT_BROWSE,
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_LEAVE,
            dataLessonAccess::EVENT_RESET,
            dataLessonAccess::EVENT_CANCEL,
            dataLessonAccess::EVENT_REFUND
        ]);
        \view::tpl("/promote/lesson")
            ->with('lesson', $lesson)
            ->with('discount', $promote['discount'] / 100)
            ->with('commission', $promote['commission'] / 100)
            ->with('recent', $recent);
    }

    public function _GET_sales()
    {
        $targetSn = $this->apiGet('target_sn');
        $srvPromote = servPromote::sole($this->platform);
        $target = $srvPromote->targetInfo($targetSn);
        $target['sn'] = $targetSn;
        $promote = $srvPromote->calc($targetSn, 0.15, 0.15);
        if ($target['lesson_id']) {
            $srvLesson = servLesson::sole($this->platform);
            $enrolled = $srvLesson->isEnrolled($target['lesson_id'], $this->uid);
        } else {
            $enrolled = servUnionOrder::sole($this->platform)->isPurchased($targetSn, $this->uid);
        }
        \view::tpl("/promote/sales")
            ->with('target', $target)
            ->with('discount', $promote['discount'] / 100)
            ->with('commission', $promote['commission'] / 100)
            ->with('enrolled', $enrolled);
    }

    public function _POST_create($opt = null)
    {
        $targetSn = $this->apiPOST('target_sn');
        $img = $this->apiPOST('img', false);
        $srvPromote = servPromote::sole($this->platform);
        $target = $srvPromote->targetInfo($targetSn);
        if ($target['lesson_id'] && !servLesson::sole($this->platform)->isEnrolled($target['lesson_id'], $this->uid)) {
            $this->apiFailure(self::ERROR_CREATE_UNENROLL);
        }
        if ($target['series_id'] && !servUnionOrder::sole($this->platform)->isPurchased($targetSn, $this->uid)) {
            $this->apiFailure(self::ERROR_CREATE_UNENROLL);
        }
        switch ($opt) {
            case 'reward':
                $vsn = $srvPromote->getReward($this->uid, $targetSn);
                break;
            case 'sales':
                $vsn = $srvPromote->genVoucher($this->uid, $targetSn, 0.15, 0.15);
                break;
            default:
                $vsn = false;
                break;
        }
        if ($vsn) {
            $domain = \config::load('boot', 'public', 'domain', '', 'Student');
            $url = "$_SERVER[REQUEST_SCHEME]://$domain/promote-receive?sn=$vsn";
            if ($img) {
                $qrcodeUrl = servQiniu::inst()->returnQrcode($url);
                if (!$qrcodeUrl) {
                    $this->apiFailure(self::ERR_UNDEFINED);
                }
                $cardUrl = \view::upload("promote/back/" . $targetSn);
                $name = servUser::sole($this->platform)->uid2info($this->uid, 'name')['name'];
                $url = servQiniu::inst()->promoteImgUrl(
                    $cardUrl,
                    $qrcodeUrl,
                    $name);
            }
            $this->apiSuccess($url);
        }
        $this->apiFailure(self::ERR_UNDEFINED);

    }

    function _GET_invite()
    {
        $token = \input::get('token')->value();
        if ($sn = servPromote::sole($this->platform)->assign($this->uid, $token)) {
            $domain = \config::load('boot', 'public', 'domain');
            $this->httpLocation("$_SERVER[REQUEST_SCHEME]://$domain/promote-receive?sn=$sn");
        } else {
            echo "链接已失效";
        }
    }

    public function _GET_haggle($opt = null)
    {
        $srvPromote = servPromote::sole($this->platform);
        switch ($opt) {
            case 'init':
                $targetSn = \input::get('target_sn')->value();
                $sn = $srvPromote->getHaggle($this->uid, $targetSn);
                $this->httpLocation("/promote-haggle?sn=$sn");
                break;
            default:
                $sn = \input::get('sn')->value();
                $data = $srvPromote->parseHaggle($sn);
                $share = new servShare();
                $domain = \config::load('boot', 'public', 'domain', '', 'Student');
                $_domain = \config::load('boot', 'public', 'domain', '', '_');
                $url = "$_SERVER[REQUEST_SCHEME]://$domain$_SERVER[REQUEST_URI]";
                $type = $data['lesson']['type'] == 'series' ? 'series' : 'detail';
                $detailUrl = "$_SERVER[REQUEST_SCHEME]://$_domain/lesson/$type/{$data['lesson']['sn']}";
                $wxConfig = $share->wxGetConfig(urldecode($url));
                if ($this->usn == $data['user']['sn']) {
                    setcookie('haggle_startup', 1, strtotime('+1 days'));
                }
                \view::tpl("/promote/haggle")
                    ->with('sn', $sn)
                    ->with('usn', $this->usn)
                    ->with('wxConfig', $wxConfig, false)
                    ->with('haggle', $data['haggle'])
                    ->with('lesson', $data['lesson'], false)
                    ->with('user', $data['user'], true)
                    ->with('detailUrl', $detailUrl)
                    ->with('startup', $_COOKIE['haggle_startup']??0)
                    ->with('helpers', $data['helpers'], true);
        }
    }

    public function _POST_haggle($opt)
    {
        $srvPromote = servPromote::sole($this->platform);
        switch ($opt) {
            case 'init':
                $targetSn = \input::post('target_sn')->value();
                $sn = $srvPromote->getHaggle($this->uid, $targetSn);
                $this->apiSuccess($sn);
                break;
            case 'help':
                $sn = \input::post('sn')->value();
                $result = $srvPromote->addHaggle($sn, $this->uid);
                if ($result->error) {
                    $this->apiFailure(self::ERR_UNDEFINED, [$result->message]);
                } else {
                    $this->apiSuccess($result->data);
                }
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, 'Illegal option');
                break;
        }
    }

    public function _GET_img()
    {
        $url = $this->apiGET('url');
        \view::tpl("/promote/receive-img")
            ->with('url', $url);
    }

    public function _GET_card()
    {
        $targetSn = $this->apiGET('target_sn');
        $domain = \config::load('boot', 'public', 'domain', null, '_');
        $this->httpLocation("$_SERVER[REQUEST_SCHEME]://$domain/promote/invite?sn=$targetSn");
        exit;
        $domain = \config::load('boot', 'public', 'domain', null, 'Student');


        $target = servPromote::sole($this->platform)->targetInfo($targetSn);
        if ($target['lesson_id']) {
            $conf = \_\servLesson::sole($this->platform)->activityConf($targetSn);
            $_conf = \_\servLesson::sole($this->platform)->conf($targetSn);
        } else {
            $conf = \_\servLessonSeries::sole($this->platform)->activityConf($targetSn);
            $_conf = \_\servLessonSeries::sole($this->platform)->conf($targetSn);
        }
        if ($this->_URI_ != $_conf['activity']['href']) {
            $this->httpLocation($_conf['activity']['href']);
            exit;
        }
        $commissionRatio = $conf['commissionRatio'] ?? 0.3;
        $force = 0;
        $discountRatio = $conf['discountRatio'] ?? 0;
        if ($targetSn == 'S59ffcaacd9c4d' || $targetSn == 'L5a030660af352' || $targetSn == 'L5a02a969a1d46' || $targetSn == 'L59ffe3e32d3c3' || $targetSn == 'L5a02a65fb4995') {
            $path = '/promote/shengcai';
            $commissionRatio = 0.15;
            $discountRatio = 0;
            $force = 1;
        } else {
            $path = '/promote/card';
        }
        //创建自主分销
        if ($discountRatio == 0) {
            $force = 1;
        }
        $sn = servPromote::sole($this->platform)->genVoucher($this->uid, $targetSn, $discountRatio, $commissionRatio);
        $shareUrl = "$_SERVER[REQUEST_SCHEME]://$domain/promote-redeem?sn=" . $sn . "&force=$force";
        $user = servUser::sole($this->platform)->uid2profile($this->uid);

        if ($conf['data']) {
            foreach ($conf['data'] as &$v) {
                foreach ($v as $k => &$v2) {
                    switch ($v2[0]) {
                        case 0:
                            $v2[1] = \view::upload($v2[1]);
                            break;
                        case 1.1:
                            $v2[1] = \view::upload("user/$this->usn/avatar?roundPic/radius/!50p");
                            break;
                        case 1.2:
                            $v2[1] = "$_SERVER[REQUEST_SCHEME]://$domain/sign-qrCode?url=" . base64_encode($shareUrl);
                            break;
                        case 2.1:
                            $v2[1] = $target['title'];
                            break;
                        case 2.2:
                            $v2[1] = $user['name'];
                            break;

                        default:
                            break;
                    }
                }
            }
        }
        if ($conf['menu_icon']) {
            foreach ($conf['menu_icon'] as &$v) {
                $v = \view::upload($v);
            }
        }
        $menuIcon = $conf['menu_icon'] ?? [];
        $prefix = \config::load('boot', 'public', 'assets', '/assets', 'Student');
        $share = new servShare();
        $url = "$_SERVER[REQUEST_SCHEME]://$domain$_SERVER[REQUEST_URI]";
        $wxConfig = $share->wxGetConfig(urldecode($url));


        $avatar = \view::upload("user/$this->usn/avatar?roundPic/radius/!50p");
        $qrUrl = "$_SERVER[REQUEST_SCHEME]://$domain/sign-qrCode?url=" . base64_encode($shareUrl);

//        $avatar = 'http://oorfr7tm0.bkt.clouddn.com/user/U5969ce16c1bce/avatar?roundPic/radius/!50p';
//        $qrUrl = 'http://oorfr7tm0.bkt.clouddn.com/card/tmp/qrcode/5a1396795c742.png';
//        $conf['data'] = '';
        $promote = servPromote::sole($this->platform)->calc($targetSn, $discountRatio, $commissionRatio);
        if ($conf['data']) {
            $listData = $conf['data'];
        } else {
            /*
             * 0-背景图   图片名  位置(0,0) 图片尺寸
             *
             * 1-图片  图片名  位置 图片尺寸
             * 1.1 头像
             * 1.2 二维码
             *
             * 2-文字（文字一般放最后）内容  大小  颜色 位置 对齐方式(left center right)
             * 2.1 title
             * 2.2 昵称
             */
            $listData = [
                [
                    [0, \view::src('img/invite-card.png'), 0, 0, 690, 1031],  //0-背景图 1-图片 2-文字（文字放最后）1.1-头像 1.2 二维码 2.1 title 2.2 昵称
                    [1.1, $avatar, 302, 125, 86, 86],
                    [1.2, $qrUrl, 210, 690, 124, 124],
                    [2, $user['name'], 26, '#333', 345, 266, 'center'],
                    [2, '邀您一起观看', 26, '#666', 345, 390, 'center'],
                    [2, $target['title'], 30, '#333', 345, 550, 'center'],
                ]
            ];
        }


        \view::tpl($path)
            ->with('target_sn', $targetSn)
            ->with('promote', $sn)
            ->with('url', $shareUrl, false)
            ->with('user', $user)
            ->with('wxConfig', $wxConfig, false)
            ->with('name', $target['title'], false)
            ->with('cover', $target['cover'])
            ->with('content', $target['brief'])
            ->with('commission', $promote['commission'] / 100)
            ->with('avatar', $avatar)
            ->with('prefix', $prefix)
            ->with('qr_url', $qrUrl)
            ->with('menu_icon', $menuIcon)
            ->with('list_data', $listData, false);
    }

    public function _GET_qrCode()
    {
        $url = $this->apiGET('url');
        $url = base64_decode($url);
        Header("Content-type: image/jpg");
        QRcode::png($url, false, QR_ECLEVEL_H, 7, 1);
    }


    public function _GET_rank($opt)
    {
        switch ($opt) {
            case 'locate':
                $target = $this->apiGET('target');
                $data = servPromote::sole($this->platform)->rankLocate($target, $this->usn);
                $this->apiSuccess($data);
                break;
            case 'slice':
                $target = $this->apiGET('target');
                $cursor = $this->apiGET('cursor', 0);
                $limit = $this->apiGET('limit', 10);
                $srvCache = servCache::sole($this->platform);
                if ($cursor >= 20) {
                    $this->apiSuccess([]);
                }
                $ckey = servCache::TAG_LESSON_RANK_SLICE . "$target|$cursor|$limit";
                if (($data = $srvCache->getJson($ckey)) === null) {
                    $data = servPromote::sole($this->platform)->rankSlice($target, $cursor, $limit);
                    $srvCache->setJson($ckey, $data, SECONDS_MINUTE);
                }
                $this->apiSuccess($data);
                break;
        }
    }
}