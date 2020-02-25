<?php


namespace _;


use Core\unitInstance;

class servLessonSeries extends serv_
{
    use unitInstance;

    const STATUS_MAP = [
        dataLessonSeries::STATUS_DENIED => 'deny',
        dataLessonSeries::STATUS_SUBMIT => 'submit',
        dataLessonSeries::STATUS_OPENED => 'opened',
        dataLessonSeries::STATUS_HALTED => 'halted'
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    /**
     * 创建系列
     * @param $uid
     * @param $name
     * @param unitIntroduce $introduce
     * @param unitLessonSeriesScheme $scheme
     * @return bool|string
     */
    public function create($uid, $name, unitIntroduce $introduce, unitLessonSeriesScheme $scheme)
    {
        if ($introduce->cover) {
            $realCover = str_replace('draft', 'series', $introduce->cover);
            //七牛图片设置为永久有效
            servQiniu::inst()->deleteAfterDays($introduce->cover, 0);
            servQiniu::inst()->move($introduce->cover, $realCover);
            $introduce->cover = $realCover;
        }

        $seriesSn = dataLessonSeries::sole($this->platform)->append($uid, $name, $introduce, $scheme);
        if ($seriesSn) {
            dataTicket::sole($this->platform)->commit(dataTicket::TYPE_CREATE_SERIES, $uid, [
                'series_sn' => $seriesSn
            ]);

            //向管理员发送审核通知
            $info = servUser::sole($this->platform)->uid2info($uid, 'name, telephone');
            servMpMsg::sole($this->platform)->callNotice(
                '您好，讲师 ' . $info['name'] . ' 创建了系列课《' . $name . '》,有待审核',
                $info['name'] . "\r\n系列课串码：" . $seriesSn,
                date('Y-m-d H:i'),
                $info['telephone']
            );
        }
        return $seriesSn;

    }

    /**
     * 编辑系列
     * @param $sn
     * @param $name
     * @param unitIntroduce $introduce
     * @param unitLessonSeriesScheme $scheme
     * @return bool
     */
    public function modify($sn, $name, unitIntroduce $introduce, unitLessonSeriesScheme $scheme)
    {
        return dataLessonSeries::sole($this->platform)->modify($sn, $name, $introduce, $scheme);
    }

    /**
     * 提交工单修改
     * @param $seriesSn
     * @param $params
     * @param $uid
     * @param $review
     * @return bool| true
     */
    public function modify2($seriesSn, $params, $uid, $review = true)
    {
        $detail = $this->detail($seriesSn);
        if ($detail && $detail['uid'] == $uid) {
            if (isset($params['cover'])) {
                $realCover = str_replace('draft', 'series', $params['cover']);
                $coverPath = $detail['introduce']['cover'] ?? '';
                if ($params['cover'] != $coverPath) {
                    $params['introduce']['cover'] = $realCover;
                    //七牛图片设置为永久有效
                    servQiniu::inst()->deleteAfterDays($params['cover'], 0);
                    servQiniu::inst()->move($params['cover'], $realCover);
                }
                unset($params['cover']);
            }
            if (isset($params['content'])) {
                $params['introduce']['content'] = $params['content'];
                unset($params['content']);
            }
            if (isset($params['price'])) {
                $params['scheme']['price'] = $params['price'] * 100;
                unset($params['price']);
            }
            if (isset($params['share'])) {
                $params['scheme']['share'] = $params['share'];
                unset($params['share']);
            }
            //提交工单
            $ret = dataTicket::sole($this->platform)->commit(dataTicket::TYPE_MODIFY_SERIES, $uid, $params);



            if ($ret && !$review) {
                servTicket::sole($this->platform)->dealSeries($uid, $ret, dataTicket::STATUS_AGREE, '');
            } else {
                //向管理员发送审核通知
                $info = servUser::sole($this->platform)->uid2info($uid, 'name, telephone');
                $name = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], 'name', 0);
                servMpMsg::sole($this->platform)->callNotice(
                    '您好，讲师 ' . $info['name'] . ' 修改了系列课《' . $name . '》,有待审核',
                    $info['name'] . "\r\n系列课串码：" . $seriesSn,
                    date('Y-m-d H:i'),
                    $info['telephone']
                );
            }
            return $ret;
        }
        return false;
    }

    /**
     * 按出品人列出系列
     * @param $uid
     * @param $alt
     * @param $seriesSn
     * @return array
     */
    public function listByUid($uid, $alt = false, $seriesSn = '')
    {
        $where['uid'] = $uid;
        if ($alt) {
            $where[] = 'i_status > 0';
        }
        $list = dataLessonSeries::sole($this->platform)->fetchAll(
            $where,
            ['sn', 'name', 'introduce', 'scheme', 'i_status', 'lesson_ids']
        );
        $existSeries = false;
        foreach ($list as &$item) {
            $item['introduce'] = json_decode($item['introduce'], true);
            $item['scheme'] = json_decode($item['scheme'], true);
            $item['lesson_ids'] = json_decode($item['lesson_ids'], true);
            $item['introduce']['cover'] = \view::upload($item['introduce']['cover']);
            $item['scheme']['price'] /= 100;
            $item['scheme']['total'] = count($item['lesson_ids']);
            if ($item['lesson_ids']) {
                $item['scheme']['opened'] = dataLesson::sole($this->platform)->fetchByIDs($item['lesson_ids'], "count(*) as cnt", ['i_step>' . dataLesson::STEP_OPENED])[0]['cnt'];
            } else {
                $item['scheme']['opened'] = 0;
            }
            $item['step'] = self::STATUS_MAP[$item['i_status']];
            unset($item['i_status'], $item['lesson_ids']);
            if ($seriesSn && $seriesSn == $item['sn']) {
                $existSeries = true;
            }
        }
        if (!$existSeries) {
            $detail = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], ['sn', 'name', 'introduce', 'scheme', 'i_status']);
            if ($detail) {
                $detail['introduce'] = json_decode($detail['introduce'], true);
                $detail['introduce']['cover'] = \view::upload($detail['introduce']['cover']);
                $detail['scheme'] = json_decode($detail['scheme'], true);
                $detail['scheme']['price'] /= 100;
                $detail['step'] = self::STATUS_MAP[$detail['i_status']];
                $list[] = $detail;
            }

        }
        return $list;
    }

    /**
     * 列出系列中的课程
     * @param $sn
     * @param $lessonSn
     * @param $stu
     * @return array
     */
    public function listLesson($sn, $stu = false, $lessonSn = '')
    {
        $ids = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $sn],
            'lesson_ids',
            0
        );
        $IDs = json_decode($ids, true);
        if ($lessonSn) {
            $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
            unset($IDs[array_search($lessonId, $IDs)]);
        }
        $lessons = $IDs ? dataLesson::sole($this->platform)->fetchByIDs($IDs) : [];
        $res = [];
        foreach ($lessons as $k => $lesson) {
            $categoryCheck = json_decode($lesson['extra'], true)['category_check'] ?? 0;
            $lesson['category_check'] = $categoryCheck;
            if ($stu && (!$categoryCheck || $lesson['i_step'] < 1)) {
                continue;
            }
            $lesson['revenue'] = stats\servLesson::sole($this->platform)->getIncome($lesson['id']);
            $lesson['stats'] = stats\servLesson::sole($this->platform)->getSummary($lesson['id']);
            $plan = json_decode($lesson['plan'], true);
            $sk = ($plan['dtm_start']??'-')."-$lesson[sn]";
            $res[$sk] = servLesson::sole($this->platform)->boost($lesson);
        }
        ksort($res);
        return array_values($res);
    }

    public function checkLessons($sn, $susn)
    {
        $suid = servUser::sole($this->platform)->usn2uid($susn);
        $ids = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $sn],
            'lesson_ids',
            0
        );
        $IDs = json_decode($ids, true);
        $lessons = $IDs ? dataLesson::sole($this->platform)->fetchByIDs($IDs, ['id','sn', 'i_step', 'i_form', 'title', 'extra']) : [];
        $res = [
            'lesson' => count($lessons),
            'enroll' => [], // 可报名课程
            'access' => [], // 可观看课程
            'refund' => [], // 可退款课程
            'events' => [], // 当前课程状态
        ];
        $srvLesson = servLesson::sole($this->platform);
        foreach ($lessons as $lesson) {

            $_extra = json_decode($lesson['extra'], true);
            if (!($_extra['category_check'] ?? null)) {
                continue;
            }

            $lesson['step'] = servLesson::STEP_MAP[$lesson['i_step']];
            $lesson['form'] = servLesson::FORM_MAP[$lesson['i_form']];
            unset($lesson['id'], $lesson['i_step'], $lesson['i_form']);

            $recent = $srvLesson->recent($lesson['sn'], $suid, [
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS,
                dataLessonAccess::EVENT_REFUND,
                dataLessonAccess::EVENT_RESET
            ]);
            if ($recent['refund_mode'] == 'freely') {
                $res['refund'][] = $lesson;
            }

            switch ($recent['event']) {
                case '':
                case 'reset':
                    $res['enroll'][] = $lesson;
                    break;
                case 'enroll':
                case 'access':
                    $res['access'][] = $lesson;
                    break;
            }

            $res['events'][$lesson['sn']] = $recent['event'];

        }
        return $res;
    }

    /**
     * 求和系列中所有课程总价
     * @param $sn
     * @return float|int
     */
    public function sumCost($sn)
    {
        $ids = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $sn],
            'lesson_ids',
            0
        );
        $IDs = json_decode($ids, true);
        $prices = dataLesson::sole($this->platform)->fetchByIDs($IDs, 'price');
        return array_sum(array_column($prices, 'price'));
    }

    /**
     * 求勾选的课程总价
     * @param $lessonSns
     * @return float|int
     */
    public function sumCheck($lessonSns)
    {
        $schemes = dataLesson::sole($this->platform)->fetchBySNs($lessonSns, 'price');
        $cost = 0;
        foreach ($schemes as $scheme) {
            $cost += $scheme['price'];
        }
        return $cost;
    }

    public function detail($key, $at = 'sn')
    {
        $detail = dataLessonSeries::sole($this->platform)->inquireOne(
            [$at => $key],
            ['sn', 'uid', 'name', 'introduce', 'scheme', 'lesson_ids']
        );
        if ($detail) {
            $detail['scheme']['price'] /= 100;
            $detail['scheme']['prime'] = $this->sumCost($detail['sn']) / 100;
            $detail['scheme']['total'] = count($detail['lesson_ids']);
            $detail['scheme']['opened'] = dataLesson::sole($this->platform)->fetchByIDs($detail['lesson_ids'], "count(*) as cnt", ['i_step>' . dataLesson::STEP_OPENED])[0]['cnt'];
            if (isset($detail['introduce']['cover'])) {
                $detail['introduce']['cover'] = \view::upload($detail['introduce']['cover']);
            }
            if (isset($detail['introduce']['banner'])) {
                $detail['introduce']['banner'] = \view::upload($detail['introduce']['banner']);
            }
            $detail['teacher'] = servUser::sole($this->platform)->uid2profile($detail['uid']);
            $detail['teacher']['about'] = servTeacher::sole($this->platform)->datum($detail['uid'])['about'];
            unset($detail['lesson_ids']);
        }
        return $detail;
    }

    public function sn2info($sn, $fields='*')
    {
        return dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $sn], $fields);
    }

    public function sn2profile($sn)
    {
        $fields = ['sn', 'uid', 'i_status', 'name', 'introduce', 'scheme', 'lesson_ids'];
        $res = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $sn], $fields);
        if (!$res) {
            return [];
        }
        $lessons = dataLesson::sole($this->platform)->fetchByIDs($res['lesson_ids'], ['id', 'sn', 'price', 'i_step', 'extra']);
        $enrolls = [0];
        $cntTotal = $cntOpened = 0;
        $oprice = 0;
        foreach ($lessons as $lesson) {
            $_extra = json_decode($lesson['extra'], true);
            if (!($_extra['category_check'] ?? null)) {
                continue;
            }
            $enrolls[] = stats\servLesson::sole($this->platform)->getSummary($lesson['id'])['lesson.enroll.unique']??0;
            $cntTotal ++;
            $cntOpened = $cntOpened + (($lesson['i_step'] > dataLesson::STEP_OPENED) ? 1 : 0);
            $oprice += $lesson['price'];
        }
        $profile = [
            'sn' => $res['sn'],
            'type' => 'series',
            'status' => self::STATUS_MAP[$res['i_status']],
            'title' => $res['name'],
            'cover' => \view::upload($res['introduce']['cover']),
            'banner' => \view::upload($res['introduce']['banner']??$res['introduce']['cover']),
            'teacher' => servUser::sole($this->platform)->uid2profile($res['uid']),
            'progress' => [$cntTotal, $cntOpened],
            'enrollment' => max($enrolls),
            'price' => $res['scheme']['price']/100,
            '_price' => $oprice/100,
        ];
        return $profile;
    }

    public function sn2introduce($sn)
    {
        $res = dataLessonSeries::sole($this->platform)->fetchOne(['sn'=>$sn], ['introduce'], 0);
        return json_decode($res, true);
    }

    public function sn2relative($sn)
    {
        $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $sn], ['lesson_ids']);
        $dao = dataLesson::sole($this->platform);
        $fields = ['id', 'sn', 'category', 'title', 'tuid', 'price', 'plan', 'i_step', 'i_form'];
//        $raws = $dao->fetchAll(['category' => $sn, 'i_step>0', 'i_form>0', 'extra->"$.category_check"=1', "sn <> ?" => [$sn]], $fields, null, null, "order by plan->'$.dtm_start'");
        $filter = ['i_step>0', 'i_form>0', 'extra->"$.category_check"=1'];
        $raws = $dao->fetchByIDs($series['lesson_ids'], $fields, $filter, "order by plan->'$.dtm_start'");
        $res = [];
        $srvLesson = servLesson::sole($this->platform);
        foreach ($raws as $raw) {
            $res[] = $srvLesson->boostProfile($raw);
        }
        return $res;
    }

    public function updateCheck($seriesSn, $lessonSns, $price)
    {
        $detail = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $seriesSn],
            ['sn', 'uid', 'name', 'lesson_ids', 'introduce', 'scheme']
        );
        if ($detail) {
            //处理总价
            $scheme = json_decode($detail['scheme'], true);
            $scheme['price'] = $price;
            $detail['scheme'] = json_encode($scheme);
            dataLessonSeries::sole($this->platform)->update($detail, ['sn' => $seriesSn]);

//            return $detail['lesson_ids'];
            //处理课程是否勾选
            $lessonIds = json_decode($detail['lesson_ids']);
            //勾选
            foreach ($lessonSns as $lessonSn) {
                $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
                $extra = servLesson::sole($this->platform)->id2extra($lessonId);
                $extra['category_check'] = 1;
                dataLesson::sole($this->platform)->update(['extra' => json_encode($extra)], ['id' => $lessonId]);
                unset($lessonIds[array_search($lessonId, $lessonIds)]);
            }

            //未勾选
            foreach ($lessonIds as $lessonId) {
                $extra = servLesson::sole($this->platform)->id2extra($lessonId);
                $extra['category_check'] = 0;
                dataLesson::sole($this->platform)->update(['extra' => json_encode($extra)], ['id' => $lessonId]);
            }
            return true;
        }
        return false;
    }

    /**
     * 添加系列课判断
     * @param $uid
     * @param $category
     * @param string $token 邀请其他讲师创建的验证token
     * @return bool
     */
    public function check($uid, $category, $token = '')
    {
        if ($token && data::redis()->get("SERIES_INVITE_$token") == $category) {
            return true;
        } else {
            $sn = dataLessonSeries::sole($this->platform)->fetchOne(
                ['sn' => $category, 'uid' => $uid], 'sn', 'sn');
            if ($sn) {
                return true;
            }
        }

        return false;
    }

    public function status($seriesSn, $iStatus = null)
    {
        $dao = dataLessonSeries::sole($this->platform);
        if ($iStatus) {
            $res = $dao->update(['i_status' => $iStatus], ['sn' => $seriesSn])->rowCount();
        } else {
            $res = $dao->fetchOne(['sn' => $seriesSn], 'i_status', 0);
        }
        return $res;
    }

    public function reviewCreate($seriesSn)
    {
        $res = servLessonSeries::sole($this->platform)->status($seriesSn, dataLessonSeries::STATUS_OPENED);
        if ($res) {
            servMpMsg::sole($this->platform)->sendSeriesReview($seriesSn, true);
        }
    }

    public function sn2id($seriesSn)
    {
        return dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], 'id', 0);
    }

    public function id2sn($seriesId)
    {
        return dataLessonSeries::sole($this->platform)->fetchOne(['id' => $seriesId], 'sn', 0);
    }

    public function conf($seriesSn)
    {
        $conf = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], "extra->'$.conf'", 0);
        if ($conf) {
            return json_decode($conf, true);
        } else {
            $menu = [
                [
                    'text' => '返回首页',
                    'href' => '/',
                ],
                [
                    'text' => '优惠活动',
                    'href' => "/promote?target_sn=$seriesSn"
                ]
            ];
            $activity = [
                'text' => '邀请有奖',
                'href' => "/promote/invite?sn=$seriesSn"
            ];
            $conf = [
                'menu' => $menu,
                'activity' => $activity,
            ];
            return $conf;
        }
    }

    public function updateConf($seriesSn, $conf)
    {
        $extra = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], 'extra', 0);
        $extra = json_decode($extra, true);
        $extra['conf'] = json_decode($conf, true);
        return dataLessonSeries::sole($this->platform)->update(['extra' => json_encode($extra)], ['sn' => $seriesSn])->rowCount();
    }

    public function activityConf($seriesSn)
    {
        $conf = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], 'extra->"$.activity_conf"', 0);
        if ($conf) {
            return json_decode($conf, true);
        } else {
            $menuIcon = [];
            return [
                'commissionRatio' => 0.3,
                'discountRatio' => 0.0,
                'menu_icon' => $menuIcon,
                'data' => [],
            ];
        }
    }

    public function updateActivityConf($seriesSn, $conf)
    {
        $extra = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], 'extra', 0);
        $extra = json_decode($extra, true);
        $extra['activity_conf'] = json_decode($conf, true);
        return dataLessonSeries::sole($this->platform)->update(['extra' => json_encode($extra)], ['sn' => $seriesSn])->rowCount();
    }

    public function updateByparams($seriesSn, $params, $cover, $banner)
    {
        $series = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], ['sn', 'uid', 'name', 'introduce', 'scheme']);
        if ($series) {
            $series['introduce'] = json_decode($series['introduce'], true);
            $series['scheme'] = json_decode($series['scheme'], true);

            if (isset($params['name'])) {
                $series['name'] = $params['name'];
            }
            if (isset($params['content'])) {
                $series['introduce']['content'] = $params['content'];
            }
            if (isset($params['price'])) {
                $series['scheme']['price'] = $params['price'] * 100;
            }
            if (isset($params['share'])) {
                $series['scheme']['share'] = $params['share'];
            }
            if (!empty($cover)) {
                //删除旧图
                if (isset($series['introduce']['cover'])) {
                    servQiniu::inst()->delete($series['introduce']['cover']);
                }
                $key = uniqid('series/cover/');
                servQiniu::inst()->putFile($key, $cover);
                $series['introduce']['cover'] = $key;
            }

            if (!empty($banner)) {
                //删除旧图
                if (isset($series['introduce']['banner'])) {
                    servQiniu::inst()->delete($series['introduce']['banner']);
                }
                $key = uniqid('series/banner/');
                servQiniu::inst()->putFile($key, $banner);
                $series['introduce']['banner'] = $key;
            }


            $series['introduce'] = json_encode($series['introduce']);
            $series['scheme'] = json_encode($series['scheme']);
//            return $series;
            return dataLessonSeries::sole($this->platform)->update($series, ['sn' => $seriesSn])->rowCount();

        }

        return false;
    }


}