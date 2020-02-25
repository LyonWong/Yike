<?php


namespace _\api;


use _\config;
use _\data;
use _\servCache;
use _\servLesson;
use _\servLessonHub;
use _\servLessonSeries;
use _\servPromote;
use _\servUser;

class ctrlPromote extends ctrlSigned
{
    public function _GET_invitation()
    {
        $sn = $this->apiGET('sn');
        $origin = $this->apiGET('origin', '');
        $data = [];
        if ($sn[0] == data::SN_LESSON) {
            $conf = servLesson::sole($this->platform)->conf($sn) ?? [];
        }
        if ($sn[0] == data::SN_SERIES) {
            $conf = servLessonSeries::sole($this->platform)->conf($sn) ?? [];
        }
        $tinfo = servPromote::sole($this->platform)->targetInfo($sn);
        $data['cards'] = array_merge($conf['invitation']['cards'] ?? [], $this->_cards(array_keys($conf['invitation']['form']??['default' => []])));
        $extra = $origin ? ['origin'=>$origin] : [];
        $psn = servPromote::sole($this->platform)->genVoucher($this->uid, $sn, $conf['discount'] ?? 0, $conf['commission'] ?? 0.3, $extra);
        $pinfo = servPromote::sole($this->platform)->attr($psn);
        $tuser = servUser::sole($this->platform)->uid2profile($tinfo['uid']);
        $user = servUser::sole($this->platform)->usn2profile($this->usn);
        $tinfo['teacher'] = $tuser['name'];
        $tinfo['price'] /= 100;
        $tinfo['username'] = $user['name'];
        $dtmStart = $tinfo['plan']['dtm_start'] ?? null;
        if ($dtmStart) {
            $tinfo['time'] = strToDate($dtmStart, 'm月d日 H:i');
        } else {
            $tinfo['time'] = '';
        }

        $data['benefits'] = [
            'discount' => $pinfo['discount'] / 100,
            'commission' => $pinfo['commission'] / 100,
        ];

        $domain = config::load('boot', 'public', 'domain');
        $sdomain = \config::load('boot', 'public', 'domain', null, 'Student');
//        $qrurl = "$_SERVER[REQUEST_SCHEME]://$domain/promote/invitation?sn=$psn";
        $force = $conf['invitation']['force'] ?? 1;
        $qrurl = "$_SERVER[REQUEST_SCHEME]://$sdomain/promote-redeem?sn=$psn&force=$force";
        foreach ($data['cards'] as $id => &$card) {
            $card['desc'] = $card['desc'] ?? [];
            if (substr($card['icon'] ?? '', 0, 4) != 'http') {
                $card['icon'] = \view::upload($card['icon']);
            }
            if (substr($card['base']['src'] ?? '', 0, 4) != 'http') {
                $card['base']['src'] = \view::upload($card['base']['src']);
            }
            if (empty($card['qrcode']['src'])) {
                $card['qrcode']['src'] = "$_SERVER[REQUEST_SCHEME]://$domain/make-qrcode?text=" . base64_encode("$qrurl&id=$id");
            }
            if (isset($card['avatar'])) {
                $card['avatar']['src'] = $user['avatar'];
            }
            if (isset($card['cover'])) {
                $card['cover']['src'] = $tinfo['cover'];
            }
            foreach (['title', 'price', 'teacher', 'username', 'time'] as $item)  {
                if (isset($card[$item])) {
                    $card[$item]['text'] = ($card[$item]['prefix'] ?? '').$tinfo[$item];
                    $card['desc'][] = $card[$item];
                    unset ($card[$item]);
                }
            }
            if ($f = $card['form'] ?? null) {
                $card['desc'] = array_merge($card['desc'] ?? [], $conf['invitation']['form'][$f]??[]); // 继承指定信息
            }
        }
        $this->apiSuccess($data);
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
                $ckey = servCache::TAG_LESSON_RANK_SLICE."$target|$cursor|$limit";
                if ( ($data = $srvCache->getJson($ckey)) === null ) {
                    $data = servPromote::sole($this->platform)->rankSlice($target, $cursor, $limit);
                    $srvCache->setJson($ckey, $data, SECONDS_MINUTE);
                }
                $this->apiSuccess($data);
                break;
        }
    }

    /*
     * 自动根据邀请人生成并领取邀请码
     */
    public function _POST_invited()
    {
        $sn = $this->apiPOST('sn'); // target sn
        $from = $this->apiPOST('from'); // inviter USN
        $origin = $this->apiPOST('origin'); // origin key
        $srvPromote = servPromote::sole($this->platform);
        $psn = $srvPromote->generate($sn, $from, $origin);
        $srvPromote->draw($psn, $this->uid);
        $this->apiSuccess($psn);
    }

    public function _POST_home()
    {
        $sn = $this->apiPOST('sn'); // target sn
        $from = $this->apiPOST('from');
        $srvPromote = servPromote::sole($this->platform);
        if ($sn[0] == data::SN_LESSON) {
            $lessonId = servLesson::sole($this->platform)->sn2id($sn);
            $psn = $srvPromote->check($this->uid, $lessonId, 0);
        } else if ($sn[0] == data::SN_SERIES) {
            $seriesId = servLessonSeries::sole($this->platform)->sn2id($sn);
            $psn = $srvPromote->check($this->uid, 0, $seriesId);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['Illegal sn']);
        }
        if ($psn ?? null) { // 已领有优惠券，不再领取
            $this->apiSuccess();
        } else { // 生成并领取渠道优惠券
            $psn = $srvPromote->generate($sn, $from, "home-$from");
            $srvPromote->draw($psn, $this->uid);
            $this->apiSuccess($psn);
        }
    }

    public function _POST_draw()
    {
        $psn = $this->apiPOST('psn');
        $res = servPromote::sole($this->platform)->draw($psn, $this->uid);
        $this->apiSuccess($res);
    }

    protected function _cards(array $forms)
    {
        $items = config::load('template/invite-card');
        $conf = [];
        foreach ($items as $key => $item) {
            if (array_search($item['form'], $forms) === false) {
                continue;
            }
            $_conf = [
                'base' => [
                    'src' => $item['base']['card'] ?? "invite/card/$key.png",
                    'size' => $this->_parseCoords($item['base']['size']),
                ],
                'icon' => $item['base']['icon'] ?? "invite/icon/$key.png",
                'qrcode' => [
                    'size' => $this->_parseCoords($item['qrcode']['size']),
                    'offset' => $this->_parseCoords($item['qrcode']['offset'])
                ],
                'desc' => [],
                'form' => $item['form'] ?? null,
            ];
            foreach (['title', 'teacher', 'price', 'username', 'time'] as $v) {
                if (isset($item[$v])) {
                    $item[$v]['offset'] = $this->_parseCoords($item[$v]['offset']);
                    $_conf[$v] = $item[$v];
                }
            }
            foreach (['avatar', 'cover'] as $v) {
                if (isset($item[$v])) {
                    $_conf[$v] = [
                        'size' => $this->_parseCoords($item[$v]['size']),
                        'offset' => $this->_parseCoords($item[$v]['offset']),
                    ];
                }
            }
            /*
            if (isset($item['avatar'])) {
                $_conf['avatar'] = [
                    'size' => $this->_parseCoords($item['avatar']['size']),
                    'offset' => $this->_parseCoords($item['avatar']['offset']),
                ];
            }
            */
            // 以url query形式配置描述信息
            foreach ($item['desc']??[] as $desc) {
                parse_str($desc, $_desc);
                $_desc['offset'] = $this->_parseCoords($_desc['offset']);
                $_conf['desc'][] = $_desc;
            }
            $conf[] = $_conf;
        }
        return $conf;
    }

    private function _parseCoords($str)
    {
        return json_decode("[$str]", true);
    }

}