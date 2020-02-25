<?php


namespace _;


use _\stats\servIdx;
use _\weixin\servMp;
use Core\library\Mysql;
use Core\unitInstance;
use Core\unitResult;

class servPromote extends serv_
{
    use unitInstance;

    const TAG_LIST = 'PROMOTE_LIST_';

    const TAG_DRAW = 'PROMOTE_DRAW_';

    const TAG_QUOTA = 'PROMOTE_QUOTA_';

    const TAG_ASSIGN = 'ASSIGN_TOKEN_';

    const TAG_RANK = "PROMOTE_RANK_";

    const EXPIRE_RANK = SECONDS_HOUR;

    const TYPE_MAP = [
        dataPromote::TYPE_COUPON => 'coupon',
        dataPromote::TYPE_VOUCHER => 'voucher',
        dataPromote::TYPE_HAGGLE => 'haggle',
        dataPromote::TYPE_REWARD => 'reward',
        dataPromote::TYPE_AUDITION => 'audition',
    ];


    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($uid, $targetSn, $originId = null, unitLessonPromote $unitLessonPromote)
    {
        if ($originId === null || $originId === false) {
            $user = servUser::sole($this->platform)->uid2profile($uid);
            $originId = servOrigin::sole($this->platform)->checkIn("promote-$user[sn]", "优惠促销-$user[name]");
        }
        if ($targetSn[0] == data::SN_LESSON) {
            $lessonId = servLesson::sole($this->platform)->sn2id($targetSn);
        } else {
            $lessonId = 0;
            $unitLessonPromote->seriesId = servLessonSeries::sole($this->platform)->sn2id($targetSn);
        }
        $psn = dataPromote::sole($this->platform)->append($uid, $lessonId, $originId, $unitLessonPromote);
        data::redis()->lPush(self::TAG_LIST . $psn, 0); //初始化领用列表
        return $psn;
    }

    /**
     * 创建用户自主分销券
     * @param $uid
     * @param $targetSn
     * @param $discountRatio
     * @param $commissionRatio
     * @param array $extra
     * @return bool|string
     */
    public function genVoucher($uid, $targetSn, $discountRatio, $commissionRatio, array $extra = [])
    {
        // 邀请卡对应来源继承来源
        $user = servUser::sole($this->platform)->uid2profile($uid);
        $srvOrigin = servOrigin::sole($this->platform);
        if ($extra['origin'] ?? null) {
            $oid = $srvOrigin->key2id($extra['origin']);
            $otier = $srvOrigin->tier($oid);
            unset ($otier[0]);
            $okey = implode('-', array_column($otier, 'key'));
            $oname = implode('-', array_column($otier, 'name'));
            $originId = servOrigin::sole($this->platform)->checkIn("$okey-promote-$user[sn]", "$oname-优惠促销-$user[name]");
        } else {
            $originId = $srvOrigin->checkIn("promote-$user[sn]", "优惠促销-$user[name]");
        }
        $target = $this->calc($targetSn, $discountRatio, $commissionRatio);
        $unitPromote = unitLessonPromote::inst(dataPromote::TYPE_VOUCHER);
        $unitPromote->remark = 'auto';
        $unitPromote->discount = $target['discount'];
        $unitPromote->commission = $target['commission'];
        $unitPromote->price = $target['price'];
        foreach ($extra as $key => $val) {
            $unitPromote->$key = $val;
        }
        $sn = dataPromote::sole($this->platform)->fetchOne([
            'uid' => $uid,
            'i_type' => dataPromote::TYPE_VOUCHER,
            'series_id' => $target['series_id'],
            'lesson_id' => $target['lesson_id'],
            'origin_id' => $originId,
            'remark' => $unitPromote->remark,
            'commission' => $unitPromote->commission,
            'discount' => $unitPromote->discount,
        ], 'sn', 0);
        if ($sn) {
            return $sn;
        } else {
            return $this->create($uid, $targetSn, $originId, $unitPromote);
        }
    }

    public function getReward($uid, $targetSn)
    {
        $target = $this->targetInfo($targetSn);
        if (!$sn = dataPromote::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'series_id' => $target['series_id'], 'lesson_id' => $target['lesson_id'], 'i_type' => dataPromote::TYPE_REWARD],
            'sn',
            0
        )
        ) {
            $sn = $this->genVoucher($uid, $targetSn, 0.2, 0.2, [
                'remark' => self::TYPE_MAP[dataPromote::TYPE_REWARD],
                'quantity' => 1
            ]);
        }
        return $sn;
    }

    /**
     * 生成助力优惠券
     * @param $uid
     * @param $targetSn
     * @return bool|string
     */
    public function genHaggle($uid, $targetSn)
    {
        $unitPromote = unitLessonPromote::inst(dataPromote::TYPE_HAGGLE);
        $targetInfo = $this->targetInfo($targetSn);
        if (!$init = ($targetInfo['conf']['haggle'] ?? null)) {
            return false;
        }
        $unitPromote->args = [
            'init' => $init,
            'list' => []
        ];
        $originId = servOrigin::sole($this->platform)->cache($targetSn, $uid);
        return $this->create($uid, $targetSn, $originId, $unitPromote);
    }

    public function getHaggle($uid, $targetSn)
    {
        $targetInfo = $this->targetInfo($targetSn);
        if (!$sn = dataPromote::sole($this->platform)->fetchOne(
            ['uid' => $uid, 'lesson_id' => $targetInfo['lesson_id'], 'series_id' => $targetInfo['series_id'], 'i_type' => dataPromote::TYPE_HAGGLE],
            'sn',
            0
        )
        ) {
            $redis = data::redis();
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $rkey = "PROMOTE_HAGGLE_$usn"; // 以USN设置自己的助力券，关注公众号后发送提醒
            $redis->setex($rkey, SECONDS_DAY, $targetSn);
            $sn = $this->genHaggle($uid, $targetSn);
        }
        return $sn;
    }

    public function addHaggle($psn, $uid)
    {
        $result = unitResult::inst();

        $dao = dataPromote::sole($this->platform);
        $res = $dao->fetchOne(['sn' => $psn], ['uid', 'lesson_id', 'series_id', 'discount', 'extra', 'i_status']);
        $extra = json_decode($res['extra'], true);
        $args = $extra['args'];
        if ($res['lesson_id']) {
           $targetSn = servLesson::sole($this->platform)->id2sn($res['lesson_id']);
        } else {
            $targetSn = servLessonSeries::sole($this->platform)->id2sn($res['series_id']);
        }


        //结束后不能再参与
        if ($res['i_status'] != dataPromote::STATUS_AVAILABLE) {
            return $result->err("已经结束啦");
        }

        //同用户只能参与一次
        $umap = array_column($args['list'], 1, 0);
        if (isset($umap[$uid])) {
            return $result->err("已经帮过TA了哦");
        }

        //获取随机比例
        switch ($args['init']['mode']) {
            case 'range':
                $n = $args['init']['n'] - count($args['list']); // 剩余可砍次数
                if ($n == 0) {
                    return $result->err("已达到最大助力次数，不能再省啦");
                } else {
                    $haggle = rand($args['init']['range'][0], $args['init']['range'][1]);
                }
                if ($n == 1) { // 最后一次推送提醒
                    $_n = $args['init']['n'];
                    $_d = ($res['discount'] + $haggle)/100;
                    $_t = $this->targetInfo($targetSn);
                    $_domain = config::load('boot', 'public', 'domain', null, 'Student');
                    servMpMsg::sole($this->platform)->send($res['uid'], [
                        'channel' => 'lesson_progress',
                        'first' => "已有 $_n 位好友助力，共帮您省了 ￥$_d\r\n",
                        'keyword1' => $_t['title'],
                        'keyword2' => '等待报名',
                        'keyword3' => '助力完成',
                        'url' => "https://$_domain/promote-haggle?sn=$psn",
                        'remark' => "\r\n点击详情前往报名吧"
                    ]);
                }
                break;
            default:
                $m = floor($args['init']['m']) - $res['discount']; // 剩余减价额度
                $n = $args['init']['n'] - count($args['list']); // 剩余可砍次数
                if ($n == 0) {
                    return $result->err("已经省不动啦");
                } else if ($n == 1) {
                    $haggle = $m;
                } else {
                    $haggle = min($m / $n, rand(0, $m / $n) + $m / $n / 2);
                }
                $haggle = round($haggle);
        }
        $discount = $res['discount'] + $haggle;

        //一天内累计不超过3次
        $redis = data::redis();
        $rkey = "PROMOTE_HAGGLE_$uid";
        $incr = $redis->incr($rkey);
        if ($incr > 3) {
            return $result->err("今天已经助力3次了，休息一下吧");
        }
        $response = [
            'haggle' => $haggle / 100,
        ];
        if ($incr == 1) {
            $redis->expire($rkey, SECONDS_DAY);
            if ($args['init']['nb_gift'] ?? null)  { // 新用户奖励
                $detail = servUser::sole($this->platform)->detail($uid);
                if (strtotime("$detail[tms_create] +12 hours") > time()) { // 注册不超过12小时
                    servMoney::sole($this->platform)->change(
                        dataMoney::ITEM_REWARD,
                        $uid,
                        $args['init']['nb_gift'],
                        ["nb_gift" => $psn]
                    );
                    $response['reward'] = $args['init']['nb_gift'] / 100;
                }
            }
        }

        //更新数据
        $args['list'][] = [intval($uid), $haggle];
        $extra['args'] = $args;
        $dao->update(
            ['discount' => $discount, 'extra' => json_encode($extra)],
            ['sn' => $psn])->rowCount();
        return $result->ok($response);
    }

    public function parseHaggle($psn)
    {
        $info = $this->info($psn);
        if ($info['lesson_id']) {
            $tsn = servLesson::sole($this->platform)->id2sn($info['lesson_id']);
        } else {
            $tsn = servLessonSeries::sole($this->platform)->id2sn($info['series_id']);
        }
        $lesson = servLessonHub::sole($this->platform)->profile($tsn);
        $user = servUser::sole($this->platform)->uid2profile($info['uid']);
        $helpMap = array_column($info['extra']['args']['list'], 1, 0);
        $helpers = servUser::sole($this->platform)->uids2profile(array_keys($helpMap));
        $haggle = [
            'init' => $info['extra']['args']['init'],
            'number' => count($helpMap),
            'discount' => array_sum($helpMap) / 100,
        ];
        foreach ($helpers as $uid => &$item) {
            $item['value'] = $helpMap[$uid] / 100;
        }

        $data = [
            'lesson' => $lesson,
            'user' => $user,
            'helpers' => $helpers,
            'haggle' => $haggle,
        ];
        return $data;
    }


    public function calc($targetSn, $discountRatio, $commissionRatio)
    {
        $target = $this->targetInfo($targetSn);
        $target['commission'] = $target['plan']['commission'] ?? round($target['price'] * $commissionRatio);
        $target['discount'] = $target['plan']['discount'] ?? round($target['price'] * $discountRatio);
        if ($target['discount'] > 1000) {
            $target['commission'] = round($target['commission'] / 100) * 100;
            $target['discount'] = round($target['discount'] / 100) * 100;
        } elseif ($target['discount'] > 100) {
            $target['commission'] = round($target['commission'] / 10) * 10;
            $target['discount'] = round($target['discount'] / 10) * 10;
        }
        return $target;
    }

    public function convey($sn)
    {
        $token = uniqid();
        $tkey = self::TAG_ASSIGN . $token;
        if (data::redis()->setex($tkey, SECONDS_DAY, $sn)) {
            return $token;
        } else {
            return false;
        }
    }

    public function assign($uid, $token)
    {
        $tkey = self::TAG_ASSIGN . $token;
        if ($sn = data::redis()->get($tkey)) {
            dataPromote::sole($this->platform)->update(
                ['uid' => $uid],
                ['sn' => $sn]
            );
            data::redis()->del($tkey);
            return $sn;
        } else {
            return false;
        }
    }

    public function verify($lessonSn, unitLessonPromote $unitLessonPromote)
    {
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['price']);
        switch ($unitLessonPromote->iType) {
            case dataPromote::TYPE_COUPON:
                $ver = ($lesson['price'] - $unitLessonPromote->discount) * 0.99 - $unitLessonPromote->commission;
                return $ver > 0;
                break;
            case dataPromote::TYPE_VOUCHER:
                $ver = $lesson['price'] * 0.99 * 0.5 - $unitLessonPromote->discount - $unitLessonPromote->commission;
                return $ver > 0;
                break;
            default:
                return false;
        }
    }

    public function draw($sn, $uid)
    {
        $promote = $this->attr($sn);
        if ($this->check($uid, $promote['lesson_id'], $promote['series_id']) == $sn) { // 避免重复领取
            return true;
        }
        if ($promote['i_status'] != dataPromote::STATUS_AVAILABLE) {
            return false;
        }
        if ($promote['i_type'] == dataPromote::TYPE_HAGGLE && $promote['uid'] != $uid) {
            return false; //砍价券自己自己使用
        }
        if ($sn[0] == 'Q') {
            return $this->useQuota($sn, $uid);
        }
        if ($promote['duration'] ?? null) {
            $ttl = $promote['duration'];
        } else if ($promote['expire']) {
            $ttl = strtotime($promote['expire']) - time();
            if ($ttl <= 0) {
                return false;
            }
        } else {
            $ttl = SECONDS_DAY * 3;
        }
        //todo 此处剩余数量更新不及时，可导致超领
        if ($promote['quantity']) {
            if (--$promote['quantity'] < 0) {
                return false;
            } else {
                dataPromote::sole($this->platform)->extra(['sn' => $sn], 'quantity', $promote['quantity']);
            }
        }
        $tag = $this->makeTag(self::TAG_DRAW, [
            'series_id' => $promote['series_id'],
            'lesson_id' => $promote['lesson_id'],
            'uid' => $uid
        ]);
        $key = self::TAG_LIST . $sn;
        data::redis()->lPush($key, $uid);
        data::redis()->expire($key, SECONDS_DAY * 3);
        data::redis()->setex($tag, $ttl, $sn);
        dataLessonAccess::sole($this->platform)->append($promote['lesson_id'], $uid, dataLessonAccess::EVENT_RECEIVE, [
            'promote' => $sn,
            'ip' => \input::ip()
        ]);
        return true;
    }

    public function generate($targetSn, $usn, $origin = null)
    {
        $targetInfo = $this->targetInfo($targetSn);
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        if (!$uid) {
            return null;
        }
        if ($origin) {
            $extra = [
                'origin' => $origin
            ];
        } else {
            $extra = [];
        }
        return $this->genVoucher($uid, $targetSn, $targetInfo['discount'], $targetInfo['commission'], $extra);
    }

    public function pick($sn)
    {
    }

    public function checkList($lessonId, $uid)
    {
    }

    public function audition($uid, $lessonId)
    {
        $psn = $this->check($uid, $lessonId, 0);
        if ($psn) {
            $attr = $this->attr($psn);
            if ($attr['i_type'] == dataPromote::TYPE_AUDITION) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function check($uid, $lessonId, $seriesId)
    {
        $tag = $this->makeTag(self::TAG_DRAW, [
            'series_id' => $seriesId,
            'lesson_id' => $lessonId,
            'uid' => $uid
        ]);
        return data::redis()->get($tag);
    }

    public function extime($uid, $lessonId, $seriesId)
    {
        $tag = $this->makeTag(self::TAG_DRAW, [
            'series_id' => $seriesId,
            'lesson_id' => $lessonId,
            'uid' => $uid
            ]);
        return data::redis()->ttl($tag);
    }

    public function slice($promoteSn, $cursor, $limit)
    {
        $redis = data::redis();
        $key = self::TAG_LIST . $promoteSn;
        $end = $cursor + $limit - 1;
        $uids = $redis->lRange($key, $cursor, $end);
        $euid = end($uids);
        $daoLessonAccess = dataLessonAccess::sole($this->platform);
        $promote = $this->info($promoteSn);
        if (count($uids) < $limit && $euid !== '0') {
            $filter = ["args->'$.promote'=?" => [$promoteSn]];
            if ($euid) {
                $_id = $daoLessonAccess->fetchOne(array_merge([
                    'lesson_id' => $promote['lesson_id'],
                    'uid' => $euid,
                    'i_event' => dataLessonAccess::EVENT_RECEIVE,
                ], $filter), 'id', 0);
                $filter["id < ?"] = [$_id];
            }
            $_slice = $daoLessonAccess->slice($promote['lesson_id'], intval($limit), $filter);
            $_uids = array_column($_slice, 'uid');
            if (count($_uids) < $limit) {
                $_uids[] = 0;
            }
            foreach ($_uids as $_uid) {
                $redis->rPush($key, $_uid);
            }
            $redis->expire($key, SECONDS_DAY * 3);
            $uids = array_merge($uids, $_uids);
        }
        $list = [];
        foreach ($uids as $uid) {
            if (!$uid) {
                continue;
            }
            $events = $daoLessonAccess->events($promote['lesson_id'], $uid, [
                dataLessonAccess::EVENT_RECEIVE,
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS]);
            $event = end($events);
            $list[] = [
                'user' => servUser::sole($this->platform)->uid2profile($uid),
                'event' => servLesson::ACCESS_MAP[$event['i_event']],
                'tms' => $event['tms']
            ];
        }
        return $list;
    }

    public function info($promoteSn)
    {
        $info = dataPromote::sole($this->platform)->fetchOne(
            ['sn' => $promoteSn],
            '*'
        );
        $info['type'] = self::TYPE_MAP[$info['i_type']];
        $info['extra'] = json_decode($info['extra'], true);
        $info['quantity'] = $info['extra']['quantity'] ?? null;
        $info['expire'] = $info['extra']['expire'] ?? null;
        $info['duration'] = $info['extra']['duration'] ?? null;
        $info['payoff'] = $info['extra']['payoff'] ?? null;
        $info['price'] = $info['extra']['price'] ?? null;
        return $info;
    }

    /**
     * 融合长期券和配额券的属性信息
     * @param $sn
     * @return array|bool|mixed|null
     */
    public function attr($sn)
    {
        if ($sn[0] == 'P') { //长期券
            return $this->info($sn);
        }
        if ($sn[0] == 'Q') { //配额券
            $attr = $this->getQuota($sn);
            $attr['sn'] = $sn;
            $info = $this->info($attr['promote']);
            return array_merge($info, $attr);
        }
        if ($sn[0] == 'A') {
            $res = dataLessonActivity::sole($this->platform)->fetchOne(['sn' => $sn], ['refer', 'args', 'i_status']);
            $args = json_decode($res['args'], true);
            $info['i_type'] = self::TYPE_MAP[dataPromote::TYPE_VOUCHER];
            $info['i_status'] = $res['i_status'];
            $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $res['refer']], ['id', 'price']);
            $info['lesson_id'] = $lesson['id'];
            $info['discount'] = $lesson['price'] * $args['done']['m'] / 100;
        }
        return false;
    }

    public function setQuota($promoteSn, $quantity, $expire)
    {
        $qsn = $this->uniqueSN('Q');
        $tag = self::TAG_QUOTA . $qsn;
        data::redis()->hMset($tag, [
            'promote' => $promoteSn,
            'quantity' => $quantity,
            'expire' => $expire,
        ]);
        data::redis()->expireAt($tag, strtotime($expire));
        return $qsn;
    }

    public function getQuota($quotaSn)
    {
        $tag = self::TAG_QUOTA . $quotaSn;
        return data::redis()->hGetAll($tag);

    }

    public function useQuota($quotaSn, $uid)
    {
        $tag = self::TAG_QUOTA . $quotaSn;
        $stock = data::redis()->hIncrBy($tag, 'quantity', -1);
        if ($stock >= 0) {
            $psn = data::redis()->hGet($tag, 'promote');
            /* 领取后删除会导致用户查看时，优惠券失效，故保留到自然失效
            if ($stock == 0) {
                data::redis()->del($tag);
            }
            */
            return $this->draw($psn, $uid);
        } else {
            return false;
        }
    }

    public function afterPurchase($sn)
    {
        $info = $this->info($sn);
        $srvMoney = servMoney::sole($this->platform);
        if ($info['i_type'] == dataPromote::TYPE_HAGGLE) {
            $list = $info['extra']['args']['list'];
            //发放砍价鼓励金
            foreach ($list as $item) {
                $srvMoney->change(
                    dataMoney::ITEM_REWARD,
                    $item[0],
                    $item[1],
                    ['psn' => $sn],
                    $sn
                );
            }
        }
        $this->rankModify($sn, $info['origin_id'], 1);
    }

    public function targetInfo($targetSn)
    {
        if ($targetSn[0] == data::SN_LESSON) {
            $res = dataLesson::sole($this->platform)->inquireOne(['sn' => $targetSn], ['id', 'tuid', 'title', 'price', 'i_form', 'plan', 'brief', 'extra']);
            $target = [
                'series_id' => 0,
                'lesson_id' => $res['id'],
                'uid' => $res['tuid'],
                'price' => $res['price'],
                'form' => servLesson::FORM_MAP[$res['i_form']],
                'plan' => $res['plan'],
                'title' => $res['title'],
                'brief' => $res['brief'],
                'cover' => \view::upload($res['extra']['cover'] ?? null),
                'discount' => $res['extra']['conf']['discount'] ?? 0,
                'commission' => $res['extra']['conf']['commission'] ?? 0.3,
                'conf' => $res['extra']['conf'] ?? [],
            ];
        } else {
            $res = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $targetSn], ['id', 'uid', 'name', 'scheme', 'introduce','extra']);
            $target = [
                'series_id' => $res['id'],
                'lesson_id' => 0,
                'uid' => $res['uid'],
                'title' => $res['name'],
                'form' => 'series',
                'price' => $res['scheme']['price'],
                'brief' => $res['introduce']['content'],
                'cover' => \view::upload($res['introduce']['cover'] ?? null),
                'discount' => $res['extra']['conf']['discount'] ?? 0,
                'commission' => $res['extra']['conf']['commission'] ?? 0.3,
                'conf' => $res['extra']['conf'] ?? [],
            ];
        }
        return $target;
    }

    public function rankInit($targetSn, $force = false)
    {
        $rds = data::redis();
        $zKey = self::TAG_RANK . $targetSn;
        $ttl = $rds->ttl($zKey);
        if ($ttl > 0 && !$force) {
            return 0; //缓存有效且非强制刷新时不做初始化
        }
        $zData = [];
        if ($targetSn[0] == 'L') {
            $preId = servOrigin::sole($this->platform)->key2id('promote');
            $res = stats\servOrigin::sole($this->platform)->subs($targetSn, $preId);

            $idxEnroll = servIdx::key2pos('lesson.enroll.unique');
            $idxRefund = servIdx::key2pos('lesson.refund.unique');
            foreach ($res as $oid => $item) {
                $zData[] = ($item[$idxEnroll] ?? 0) - ($item[$idxRefund] ?? 0);
                $zData[] = $oid;
            }
        }
        if ($targetSn[0] == 'S') {
            $lessonIds = dataLesson::sole($this->platform)->fetchAll(['category' => $targetSn, 'i_step>0'], ['id'], null, 0);
            $ids = Mysql::makeData($lessonIds, '?', ',');
            $cnt = count($lessonIds);
            $where = [
                "lesson_id in ($ids[clause])" => $ids['params'],
                'i_status>0'
            ];
            $res = dataOrder::sole($this->platform)->fetchAll($where,
                "origin_id, uid, count(*) as cnt", null, null,
                "group by origin_id,uid having cnt=$cnt"
            );
            $_id = servOrigin::sole($this->platform)->key2id('promote');
            $_tmp = [];
            foreach ($res as $row) {
                $ok = dataOrigin::sole($this->platform)->fetchOne(['id' => $row['origin_id'], '_id' => $_id], ['key'], 0);
                if ($ok) {
                    $_tmp[$row['origin_id']] = ($_tmp[$row['origin_id']] ?? 0) + 1;
                }
            }
            foreach ($_tmp as $_oid => $_num) {
                $zData[] = $_num;
                $zData[] = $_oid;
            }
        }
        $rds->zAdd($zKey, ...$zData);
        if ($ttl < 0 || $force) { // 新缓存设置过期时间
            $rds->expire($zKey, self::EXPIRE_RANK);
        }
    }

    public function rankLesson($lessonSn)
    {
        $preId = servOrigin::sole($this->platform)->key2id('promote');
        $res = stats\servOrigin::sole($this->platform)->subs($lessonSn, $preId);

        $idxEnroll = servIdx::key2pos('lesson.enroll.unique');
        $idxRefund = servIdx::key2pos('lesson.refund.unique');

        $ret = [];
        foreach ($res as $oid => $item) {
            $ret[$oid] = ($item[$idxEnroll] ?? 0) - ($item[$idxRefund] ?? 0);
        }
        return $ret;
    }

    public function rankModify($targetSn, $oid, $value)
    {
        $zKey = self::TAG_RANK . $targetSn;
        $this->rankInit($targetSn);
        $rds = data::redis();
        return $rds->zIncrBy($zKey, $value, $oid);
    }

    public function rankSlice($targetSn, $cursor, $limit)
    {
        $zKey = self::TAG_RANK . $targetSn;
        $this->rankInit($targetSn);
        $rds = data::redis();
        $slice = $rds->zRevRange($zKey, $cursor, $cursor + $limit - 1, true);
        $data = [];
        $srvOrigin = servOrigin::sole($this->platform);
        $srvUser = servUser::sole($this->platform);
        /* 未开课时隐藏邀请人数
        $iStep = dataLesson::sole($this->platform)->fetchOne(['sn' => $targetSn], 'i_step', 'i_step');
        if ($iStep < dataLesson::STEP_ONLIVE) {
            foreach ($slice as $oid => $score) {
                if ($score == 0) {
                    continue;
                }
                $usn = $srvOrigin->profile($oid)['key'];
                $user = $srvUser->usn2profile($usn);
                $user['score'] = '?';
                $user['rank'] = $user['cursor'] = ++$cursor;
                $data[] = $user;
            }
        } else {
        */
        foreach ($slice as $oid => $score) {
            if ($score == 0) {
                continue;
            }
            $usn = $srvOrigin->profile($oid)['key'];
            $user = $srvUser->usn2profile($usn);
            $user['score'] = $score;
            $user['rank'] = $user['cursor'] = ++$cursor;
            $data[] = $user;
        }
        return $data;
    }

    public function rankLocate($targetSn, $usn)
    {
        $this->rankInit($targetSn);
        $zKey = self::TAG_RANK . $targetSn;
        $rds = data::redis();
        $oid = servOrigin::sole($this->platform)->key2id("promote-$usn");
        $rank = $rds->zRevRank($zKey, $oid);
        $score = $rds->zScore($zKey, $oid);
        return [
            'rank' => $rank === false ? false : $rank + 1,
            'score' => $score,
        ];
    }

    public function sendPromoteMsg($uid, $targetSn)
    {
        $psn = $this->getReward($uid, $targetSn);
        $targetInfo = $this->targetInfo($targetSn);
        $domain = \config::load('boot', 'public', 'domain', null, 'Student');
        $content = [
            'articles' => [
                [
                    "title" => "易灵微课优惠券",
                    "description" => "恭喜您获得1张8折优惠券(仅好友可用)。分享给需要的朋友，邀请TA一起学习吧，您也可以获得20%鼓励金哦",
                    "url" => "https://$domain/promote-receive?sn=$psn",
                    "picurl" => $targetInfo['cover']
                ]
            ]
        ];
        return servMp::sole($this->platform)->sendCustomMessage($uid, 'news', $content);
    }

    public function sendHaggleMsg($uid, $targetSn)
    {
        $psn = $this->getHaggle($uid, $targetSn);
        $targetInfo = $this->targetInfo($targetSn);
        $domain = \config::load('boot', 'public', 'domain', null, 'Student');
        $content = [
            'articles' => [
                [
                    'title' => "易灵微课助力优惠",
                    'description' => "您正在报名《$targetInfo[title]》，邀请好友来祝您一臂之力吧",
                    'url' => "https://$domain/promote-haggle?sn=$psn",
                    'picurl' => $targetInfo['cover'],
                ]
            ]
        ];
        return servMp::sole($this->platform)->sendCustomMessage($uid, 'news', $content);
    }
}