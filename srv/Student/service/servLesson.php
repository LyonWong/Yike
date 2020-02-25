<?php


namespace Student;


use _\dataLessonAccess;
use _\dataLessonSeries;
use _\dataMoney;
use _\servMpMsg;
use _\servPromote;
use _\servRating;
use _\servTicket;
use Core\unitInstance_;
use Core\library\WechatTpl;


class servLesson extends \_\servLesson
{
    use unitInstance_;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function list($uid, unitLessonQuery $query)
    {
        $where = $query->toWhere();
        $where[] = 'i_step > 0';
        $ip = \input::ip();
        $hiddenIPs = \config::load('option', 'allowed', 'viewHidden.IPs', []);
        if (!in_array($ip, $hiddenIPs)) {
            $where[] = 'i_form > 0';
        }
        $field = ['id', 'sn', 'title', 'price', 'category', 'extra', 'i_step', 'tuid', 'plan', 'tms_update'];
        $lastLesson = dataLessonAccess::sole($this->platform)->lastLessonOfUser($uid);
        $list = dataLesson::sole($this->platform)->list($where, $field);
        $stack = [];
        $sorts = [];
        $srvSeries = servLessonSeries::sole($this->platform);
        $daoSeries = dataLessonSeries::sole($this->platform);
        $now = time();
        foreach ($list as &$item) {
            $_lessonId = $item['id'];
            $_iStep = $item['i_step'];
            $item = self::boost($item);
            if (($lastLesson['lesson_id']??0) == $_lessonId) {
                $dt = 0;
            } else {
                $dt = abs(strtotime($item['plan']['dtm_start']) - $now);
            }
            if ($item['category']) {
                if (empty($stack[$item['category']])) {
                    $series = $srvSeries->detail($item['category']);
                    $_lessonIds = $daoSeries->inquireOne(['sn' => $item['category']], ['lesson_ids'])['lesson_ids'];
                    $series['cover'] = $series['introduce']['cover'] ?? '';
                    unset($series['uid'], $series['introduce']);
                    $series['type'] = 'series';
                    $series['scheme']['total'] = count($_lessonIds);
                    $series['scheme']['opened'] = 0;
                    $sorts[$item['category']] = $dt / (($series['scheme']['price'] ?? 0) ?: 1) * $_iStep;
                    $stack[$item['category']] = $series;
                } else {
                    $sorts[$item['category']] = min($sorts[$item['category']], $dt);
                    $series = $stack[$item['category']];
                }

                if ($_iStep > dataLesson::STEP_OPENED) {
                    $series['scheme']['opened'] += 1;
                }
                $stack[$item['category']] = $series;
            } else {
                $item['type'] = 'single';
                $stack[$item['sn']] = $item;
                $sorts[$item['sn']] = $dt;
            }
        }
        $list = [];
        $_sorts = [
            'S5af1048603464' => 0.1,
            'L5af1a9df60573' => 0.11,
            'L5af55e95af5de' => 0.15,
            'S5af687b0f318c' => 0.16,
            'S5ae01a3b7881b' => 0.2,
            'S5abb3d94c02de' => 0.2, // facebook
            'S5ab35ddb1ad3b' => 0.2, //team
            'S5a4c664d47b94' => 0.2, //xiaomi
            'S5aa7e90944fc9' => 0.3, //excel
            'L5a98b7747fd87' => 0.3, //文案
            'S5a7ab2ad5369d' => 0.3, //常旅
            'S5a75eb90ef1e4' => 0.3, //python数据分析
            'L5a7860c008a99' => 0.4, //拖延症
            'L5a64c7faacea5' => 0.8, //BC
            'L5a6951f99e669' => 2.5,//微信红包
            'S5a15481107f2a' => 1, // 爬虫
            'S59ffcaacd9c4d' => 2, // 生财
            'S5a04130b819e3' => 3, // 社群
            'L59a66f25d4ade' => 4, // 小密圈
            'L59689fa0284f6' => 5, // 产品入门
        ];
        foreach ($_sorts as $_sn => $_dt) {
            if (isset ($sorts[$_sn])) {
                $sorts[$_sn] = $_dt;
            }
        }
        asort($sorts);
        foreach ($sorts as $sn => $dt) {
            $list[] = $stack[$sn];
        }
        return $list;
    }

    public function _list($uid, unitLessonQuery $lessonQuery)
    {
        $where = $lessonQuery->toWhere();
        $where[] = 'i_step > 0';
        $where[] = 'i_form > 0';
        $list = dataLesson::sole($this->platform)->list($where, [
            'id', 'sn', 'title', 'price', 'category', 'extra', 'i_step', 'tuid', 'plan', 'tms_update'
        ]);
        $lastLesson = dataLessonAccess::sole($this->platform)->lastLessonOfUser($uid);
        $fmap = [];
        foreach ($list as $i => &$item) {
            if ($lastLesson && $lastLesson['lesson_id'] == $item['id']) {
                $first = $i;
            }
            $item = self::boost($item);
            $categoryCheck = $item['extra']['category_check'] ?? 0;
            if (!$categoryCheck) {
                $item['category'] = "";
                $item['categoryInfo'] = false;
            }
            if ($item['step'] == 'opened') {
                $fkey = "{$item['teacher']['sn']}-$item[category]";
                if (isset($fmap[$fkey])) {
                    if ($fmap[$fkey]['tms'] > $item['plan']['dtm_start']) {
                        $list[$fmap[$fkey]['i']] = null;
                        $fmap[$fkey] = ['i' => $i, 'tms' => $item['plan']['dtm_start']];
                    } else {
                        $list[$i] = null;
                    }
                } else {
                    $fmap[$fkey] = ['i' => $i, 'tms' => $item['plan']['dtm_start']];
                }
            }
        }
        if (isset($first)) {
            $list = array_merge(
                [$list[$first]],
                array_slice($list, 0, $first),
                array_slice($list, $first + 1)
            );
        }
        return array_values(array_filter($list));


    }

    public function teacherList($tusn)
    {
        $tuid = servUser::sole($this->platform)->usn2uid($tusn);
        $where['tuid'] = $tuid;
        $where[] = 'i_step > 0';
        $where[] = 'i_form > 0';
        $list = dataLesson::sole($this->platform)->list($where, [
            'id', 'sn', 'title', 'price', 'category', 'brief', 'i_step', 'tuid', 'plan', 'tms_update'
        ]);
        foreach ($list as $i => &$item) {
            $item = self::boost($item);
        }
        return $list;


    }

    public function history($uid)
    {
        $res = dataLessonAccess::sole($this->platform)->history($uid);
        $tmp = [];
        foreach ($res as $row) {
            $refundInfo = servTicket::sole($this->platform)->sn2refundInfo(servLesson::sole($this->platform)->id2sn($row['lesson_id']), $uid);
            $mode = self::returnRefundMode(self::id2sn($row['lesson_id']), $uid);
            $tmp[$row['lesson_id']] = [
                'event' => self::ACCESS_MAP[$row['i_event']],
                'args' => $row['args'],
                'tms' => $row['tms'],
                'refund_mode' => $mode,
                'refund_info' => $refundInfo,
                'rated' => servRating::sole($this->platform)->rated(servLesson::sole($this->platform)->id2sn($row['lesson_id']), $uid),
                'order' => \_\servOrder::sole($this->platform)->findOrderByLessonId($uid, $row['lesson_id']),

            ];
        }
        $list = [];
        foreach ($tmp as $lessonId => $item) {
            $item['lesson'] = self::profile($lessonId, 'id');
            $categoryCheck = $item['lesson']['extra']['category_check'] ?? 0;
            if (!$categoryCheck) {
                $item['lesson']['category'] = "";
                $item['lesson']['categoryInfo'] = false;
            }
            $list[] = $item;
        }
        return $list;
    }


    public function sn2price($sn)
    {
        return dataLesson::sole($this->platform)->fetchOne(['sn' => $sn], 'price', 'price') / 100;
    }

    public function sn2title($sn)
    {
        return dataLesson::sole($this->platform)->fetchOne(['sn' => $sn], 'title', 'title');
    }

    public function isAvailable($lessonSn)
    {
        $iStep = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'i_step', 0);
        return $iStep > 0; // 大于0为可用状态
    }

    public function enroll($lessonSn, $uid, $originId, $orderId)
    {
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'price', 'category', 'extra']);
        $conf = json_decode($lesson['extra'], true)['conf'];
        if (($conf['indie'] ?? true) === false && $lesson['category']) { // indie=false禁止单独购买
            return false;
        }

        if ($lesson['price'] > 0) {
            $srvPromote = servPromote::sole($this->platform);
            $oldOrder = [];
            if($orderId) {
                $oldOrder = \_\dataOrder::sole($this->platform)->fetchOne(['id' => $orderId, 'uid' => $uid], '*');
            }
            if ($orderId && $oldOrder) {
                $orderAmount = $oldOrder['order_amount'];
                $extra = json_decode($oldOrder['extra'], true);
                $originId = $oldOrder['origin_id'];
                $psn = $extra['promote'] ?: '';
                $promote = $srvPromote->info($psn);
                $deduct = $promote['discount'] ? min($orderAmount, $promote['discount']) : 0;
                $order = servOrder::sole($this->platform)->create($uid, $lesson['id'], $orderAmount, $originId, $extra);
            } else {
                $orderAmount = $lesson['price'];
                $extra = [];
                $deduct = 0;
                if ($psn = $srvPromote->check($uid, $lesson['id'], 0)) {
                    $promote = $srvPromote->info($psn);
                    $originId = $promote['origin_id'] ?? $originId;
                    $deduct = min($orderAmount, $promote['discount']);
                    $orderAmount -= $deduct;
                    $extra = ['promote' => $psn];
                }
                if ($lesson['category']) {
                    $extra['series'] = $lesson['category'];
                }
                $order = servOrder::sole($this->platform)->create($uid, $lesson['id'], $orderAmount, $originId, $extra);
            }
            $balance = dataMoney::sole($this->platform)->balance($uid);
            $margin = $balance - $orderAmount;
            $charge = min($balance, $orderAmount);
            $surplus = max(0, $orderAmount - $balance);
            if (!$order) {
                return false;
            }

        } else {
            $order = $margin = $deduct = $charge = $surplus = null;
            $check = dataLessonAccess::sole($this->platform)->append($lesson['id'], $uid, dataLessonAccess::EVENT_ENROLL, [
                'origin' => $originId,
                'ip' => \input::ip()
            ]);
            if (!$check) {
                return false;
            } else {
                \_\stats\servLesson::sole($this->platform)->varEnroll($lesson['id'], $originId);
            }
        }
        $res = [
            'order' => $order,
            'price' => $lesson['price'] / 100,
            'margin' => $margin / 100, // 余额差额
            'deduct' => $deduct / 100, // 优惠抵扣
            'charge' => $charge / 100, // 余额抵扣
            'surplus' => $surplus / 100, // 补充支付
        ];
        return $res;
    }

    public function confirm($lessonSn, $uid)
    {
        $lessonId = $this->sn2id($lessonSn);
        return dataLessonAccess::sole($this)->append($lessonId, $uid, dataLessonAccess::EVENT_CONFIRM);
    }

    public function cancel($lessonSn, $uid)
    {
        $lessonId = $this->sn2id($lessonSn);
        return dataLessonAccess::sole($this)->append($lessonId, $uid, dataLessonAccess::EVENT_CANCEL);
    }


    public function orderStatus($orderSn, $lid, $uid)
    {
        $ret = dataOrder::sole($this->platform)->fetchOne(['sn' => $orderSn], 'lesson_id,uid,i_status');
        if ($ret && $ret['lesson_id'] == $lid && $ret['uid'] == $uid) {
            return $ret['i_status'];
        }
        return false;
    }


    public function getReward($lessonSn, $uid)
    {
        $detail = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['titile', 'price', 'tuid']);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $detail['tuid']], 'name', 'name');

        $srvPromote = servPromote::sole($this->platform);
        $psn = $srvPromote->getReward($uid, $lessonSn);
        $discount = servPromote::sole($this->platform)->attr($psn)['discount'] / 100;
        servMpMsg::sole($this->platform)->getReward($uid, $teacherName, $detail['title'], $discount, $psn);
    }

    public function sendText($content, $uid)
    {
        $info = servUser::sole($this->platform)->uid2info($uid, 'openid');
        $openid = $info['openid'];
        $sender = new WechatTpl('mp');
        return $sender->sendText($content, $openid);
    }

    public function sendPic($uid)
    {
        $info = servUser::sole($this->platform)->uid2info($uid, 'openid');
        $openid = $info["openid"];
        $sender = new WechatTpl('mp');

        //上传临时素材  测试
        $pic_url = PUBLIC_PATH . '/1.jpg';
        $ret = \json_decode($sender->uploadPic($pic_url), true);
        if (isset($ret['media_id'])) {
            //像用户发送图片
            return $sender->sendPic($ret['media_id'], $openid);
        }
        return false;

    }

    public function sendNews($uid)
    {
        //测试
        $article = array(
            'title' => 'happy day',
            'description' => "系统会生成该接口的参数表，您可以直接在文本框内填入对应的参数值。（红色星号表示该字段必填",
            'url' => 'http://www.baidu.com',
            'picurl' => 'http://wx.qlogo.cn/mmopen/PiajxSqBRaEK4n0xVPsTYoMwMJwg91EObjTrN9BvdnQvcmHlQoYzcwgo7f8SXOsZAyYKRUEQ6b2x0HqkiaJkpj6w/0',
        );
        $info = servUser::sole($this->platform)->uid2info($uid, 'openid');
        $openid = $info['openid'];
        $sender = new WechatTpl('mp');
        return $sender->sendNews($article, $openid);
    }


}