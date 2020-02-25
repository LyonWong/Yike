<?php


namespace _;


use _\weixin\serv;
use Core\unitInstance;
use Core\library\WechatTpl;


class servMpMsg extends serv_
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

    public function sendDealResult($uid, $first, $reason, $result, $remark, $openId = null)
    {
        $openId = $openId ?: servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'deal_result',
            'first' => $first,
            'reason' => $reason,
            'result' => $result,
            'remark' => $remark,
            'url' => '',
        );
        return $sender->send($data, $openId);
    }

    public function sendRefundMsg($lessonSn, $uid)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'title', 'price']);
        $order = servOrder::sole($this->platform)->findLastRefundOrder($uid, $lessonSn);
        $paidAmount = $order['paid_amount']/100;
        $restAmount = ($order['order_amount'] - $order['paid_amount'])/100;
        $sender = new WechatTpl('mp');
        //退款消息模板
        $data = array(
            'channel' => 'refund_notice',
            'first' => '你的退款申请已通过',
            'status' => '退款成功',
            'lesson' => $lessonInfo['title'],
            'money' => $order['order_amount'] / 100 . " 元\r\n退还路线：微信零钱￥$paidAmount, 账户余额￥$restAmount",
            'url' => '',
            'remark' => "\r\n欢迎继续收听我们的其他课程",
        );
        return $sender->send($data, $openId);
    }

    public function sendRefundMessage($targetSn, $uid, $money, $reason)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $target = servLessonHub::sole($this->platform)->target($targetSn);
        $type = ($targetSn[0] == 'S') ? 'series' : 'detail';
        $moneyBack = '';
        foreach ($money as $i => $amount) {
            $moneyBack .= $moneyBack ? '，' : '';
            if ($i == 'weixin') {
                $moneyBack .= "微信支付￥$amount";
            }
            if ($i == 'balance') {
                $moneyBack .= "账户余额￥$amount";
            }
        }
        $domain = config::load('boot', 'public', 'domain');
        $sender = new WechatTpl('mp');
        $data = [
            'channel' => 'refund_notice',
            'first' => $reason."\r\n",
            'status' => '退款完成',
            'lesson' => $target['title'],
            'money' => $moneyBack,
            'url' => "https://$domain/lesson/$type?sn=$targetSn&mark=refund",
            'remark' => "\r\n欢迎购买我们的其他课程"
        ];
        return $sender->send($data, $openId);

    }

    public function sendRefundApplyFailedMsg($lessonSn, $uid, $remark)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'price']);
        $orderAmount = servOrder::sole($this->platform)->findOrderAmountOrder($uid, $lessonSn);
        $sender = new WechatTpl('mp');
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student')
            . "/weixin-refund?lesson_sn=$lessonSn&cur_mode=apply";
        //退款消息模板
        $data = array(
            'channel' => 'refund_notice',
            'first' => '你的退款申请未通过',
            'status' => '未通过',
            'lesson' => $lessonInfo['title'],
            'money' => $orderAmount / 100 . ' 元' . "\r\n处理意见：" . $remark,
            'url' => $url,
            'remark' => "\r\n如有异议，可向易课平台提出申诉",
        );
        return $sender->send($data, $openId);
    }

    public function sendRefundAppealFailedMsg($lessonSn, $uid, $remark)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'price']);
        $orderAmount = servOrder::sole($this->platform)->findOrderAmountOrder($uid, $lessonSn);
        $sender = new WechatTpl('mp');
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student')
            . "/weixin-refund?lesson_sn=$lessonSn&cur_mode=appeal";
        //退款消息模板
        $data = array(
            'channel' => 'refund_notice',
            'first' => '你的退款申诉未通过',
            'status' => '申请驳回',
            'lesson' => $lessonInfo['title'],
            'money' => $orderAmount / 100 . ' 元' . "\r\n处理意见：" . $remark,
            'url' => $url,
            'remark' => "\r\n如有异议，可公众号后台回复反馈。",
        );
        return $sender->send($data, $openId);
    }

    public function sendAutoRefundMsg($lessonSn, $uid)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['title', 'price']);
        $order = servOrder::sole($this->platform)->findLastRefundOrder($uid, $lessonSn);
        $paidAmount = $order['paid_amount']/100;
        $restAmount = ($order['order_amount'] - $order['paid_amount'])/100;
        $domain = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student');
        $sender = new WechatTpl('mp');
        //退款消息模板
        $data = array(
            'channel' => 'refund_notice',
            'first' => "很多天过去了，您一直没来听课。为保障您的权益，我们已原路退还报名费\r\n",
            'status' => '退款成功',
            'lesson' => $lessonInfo['title'],
            'money' => $order['order_amount'] / 100 . " 元\r\n退还路线：微信零钱￥$paidAmount, 账户余额￥$restAmount",
            'url' => $domain . '/weixin-lesson?mark=autorefund&lesson_sn=' . $lessonSn . '&order_id=' . $order['id'],
            'remark' => "\r\n点击详情可按原价重新报名，观看回放",
        );
        return $sender->send($data, $openId);
    }

    public function sendAutoRefundSeriesMsg($seriesSn, $uid)
    {
    }

    public function sendEnrollMsg($lessonSn, $uid, $orderInfo=[])
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['title', 'price', 'plan', 'i_form']);
        $dtmStart = json_decode($lessonInfo['plan'], true)['dtm_start'];
        $dtmStart = date('Y-m-d H:i', strtotime($dtmStart));
        $sender = new WechatTpl('mp');
        $domain = \config::load('boot', 'public', 'domain');
        $originKey = servOrigin::sole($this->platform)->id2key($orderInfo['origin_id']);
        $originFrags = explode('-', $originKey);
        if ($originFrags[0] == 'home') {
            $home = "home/$originFrags[1]";
        } else {
            $home = null;
        }

        //购买课程消息模板
        switch ($lessonInfo['i_form']) {
            case dataLesson::FORM_ARTICLE:
                $data = [
                    'channel' => 'enroll_success',
                    'first' => '您已成功解锁文章'."\r\n",
                    'title' => "《$lessonInfo[title]》",
                    'time' => date('Y-m-d H:i'),
                    'url' => $home ? "https://$domain/$home/article?sn=$lessonSn" : "https://$domain/study/article/$lessonSn",
                    'remark' => "\r\n点击开始阅读",
                ];
                break;
            case dataLesson::FORM_COLUMN:
                $data = [
                    'channel' => 'enroll_success',
                    'first' => '您已成功订阅专栏'."\r\n",
                    'title' => "《$lessonInfo[title]》",
                    'time' => date('Y-m-d H:i'),
                    'url' => $home ? "https://$domain/$home/column?sn=$lessonSn" : "https://$domain/lesson/column/$lessonSn",
                    'remark' => "\r\n点击查看详情",
                ];
                break;
            default:
                $data = array(
                    'channel' => 'enroll_success',
                    'first' => '你已经成功报名课程'."\r\n",
                    'title' => '《' . $lessonInfo['title'] . '》',
                    'time' => date('Y-m-d H:i') . "\r\n开课时间：" . $dtmStart,
                    'url' => 'https://' . \config::load('boot', 'public', 'domain') . '/lesson/detail/' . $lessonSn,
                    'remark' => "\r\n点击查看详情",
                );
                break;
        }
        return $sender->send($data, $openId);
    }

    public function sendSeriesMsg($seriesSn, $uid)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $series = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], ['name']);
        $sender = new WechatTpl('mp');

//        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-series?series_sn=' . $seriesSn;
        $url = 'https://' . \config::load('boot', 'public', 'domain', '') . "/lesson/series/$seriesSn?action=study";
        //购买课程消息模板
        $data = array(
            'channel' => 'enroll_success',
            'first' => '你已经成功报名。',
            'title' => $series['name'],
            'time' => date('Y-m-d H:i'),
            'url' => $url,
            'remark' => "\r\n点击详情查看课程",
        );
        return $sender->send($data, $openId);
    }

    public function sendPromoteMsg($promoteSn, $_uid)
    {
        $promote = servPromote::sole($this->platform)->info($promoteSn);

        //判断是否设置佣金提醒
        $commissionSetting = servUser::sole($this->platform)->uid2setting($promote['uid'], dataUser::NOTICE_COMMISSION);
        if (!$commissionSetting) {
            return false;
        }

        $openId = servUser::sole($this->platform)->uid2info($promote['uid'], 'openid')['openid'];
        $_user = servUser::sole($this->platform)->uid2profile($_uid);
        if ($promote['commission'] == 0) {
            return null;
        }
        $title = '';
        if ($promote['series_id']) {
            $title = dataLessonSeries::sole($this->platform)->fetchOne(['id' => $promote['series_id']], 'name', 0);
        }
        if ($promote['lesson_id']) {
            $title = servLesson::sole($this->platform)->profile($promote['lesson_id'], 'id')['title'];
        }
        $sender = new WechatTpl('mp');
        $data = [
            'channel' => 'commission_notice',
            'first' => "{$_user['name']} 报名了您推荐的课程《{$title}》",
            'amount' => '￥' . round($promote['commission'] / 100, 2),
            'time' => date('Y-m-d H:i'),
            'remark' => "\r\n若TA听课后三天未退款，佣金将结算至您的个人账户\r\n\r\n回复『关闭佣金提醒』，不再接收此类提醒"
        ];
        return $sender->send($data, $openId);
    }

    public function sendCommissionMsg($uid, $_uid, $targetSn, $amount, $type='推荐')
    {
        //判断是否设置佣金提醒
        $commissionSetting = servUser::sole($this->platform)->uid2setting($uid, dataUser::NOTICE_COMMISSION);
        if (!$commissionSetting) {
            return false;
        }

        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $_user = servUser::sole($this->platform)->uid2profile($_uid);
        $tinfo = servLessonHub::sole($this->platform)->target($targetSn);
        $sender = new WechatTpl('mp');
        $data = [
            'channel' => 'commission_notice',
            'first' => "{$_user['name']} 报名了您{$type}的《{$tinfo['title']}》",
            'amount' => '￥' . ($amount/100),
            'time' => date('Y-m-d H:i'),
            'remark' => "\r\n若TA听课后三天未退款，佣金将结算至您的个人账户\r\n\r\n回复『关闭佣金提醒』，不再接收此类提醒"
        ];
        return $sender->send($data, $openId);
    }

    public function sendUpCome($lessonSn)
    {

        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['tuid', 'title', 'plan']);
        $info['plan'] = json_decode($info['plan'], true);
        $openId = servUser::sole($this->platform)->uid2info($info['tuid'], 'openid')['openid'];
        $sender = new WechatTpl('mp');

        $data = array(
            'channel' => 'lesson_upcome_notice',
            'first' => '您开设的课程即将开始！',
            'title' => '《' . $info['title'] . '》',
            'time' => $info['plan']['dtm_start'],
            'url' => '',
            'remark' => "\r\n请准备好麦克风，保持环境安静，按时开课。",
        );
        $this->callNotice("$info[title]即将开启", "易灵微课", $info['plan']['dtm_start'], '');
        return $sender->send($data, $openId);
    }

    public function sendOpenClass($lessonSn, $num = 10, $gap = 1)
    {

        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'plan']);
        $info['plan'] = json_decode($info['plan'], true);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);
        $count = 0;
        foreach ($lastEvents as $lastEvent) {
            if ($lastEvent['i_event'] == dataLessonAccess::EVENT_ENROLL) {
                //判断是否设置课前提醒
                $preclassSetting = servUser::sole($this->platform)->uid2setting($lastEvent['uid'], dataUser::NOTICE_PRECLASS);
                if (!$preclassSetting) {
                    continue;
                }

                $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                $sender = new WechatTpl('mp');
                $domain = \config::load('boot', 'public', 'domain', '_');
                $url = "https://$domain/lesson/course/$lessonSn?action=study";
                $data = array(
                    'channel' => 'lesson_upcome_notice',
                    'first' => '你报名的课程已开始',
                    'title' => '《' . $info['title'] . '》',
                    'time' => date('Y-m-d H:i'),
                    'url' => $url,
                    'remark' => "\r\n点击详情进入课堂",
                );
                $sender->send($data, $openId);
                if ($gap && (++$count % $num == 0)) {
                    sleep($gap);
                }
            }
        }

    }

    /**
     * 专栏更新推送
     * @param $articleSn
     * @param int $num
     * @param int $gap
     * @throws \coreException
     */
    public function sendSubscribeUpdate($articleSn, $num=10, $gap=1)
    {
        $article = dataLesson::sole($this->platform)->fetchOne(['sn' => $articleSn], ['id', 'title', 'category']);
        $column = dataLesson::sole($this->platform)->fetchOne(['sn' => $article['category']], ['id', 'title']);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($column['id']);
        $count = 0;
        foreach ($lastEvents as $lastEvent) {
            if (in_array($lastEvent['i_event'], [
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS,
                dataLessonAccess::EVENT_CONFIRM,
                dataLessonAccess::EVENT_LEAVE
            ])) {
                $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                $sender = new WechatTpl('mp');
                $domain = \config::load('boot', 'public', 'domain', '_');
                $url = "https://$domain/study/article/$articleSn";
                $data = [
                    'channel' => 'lesson_progress',
                    'first' => "您订阅的专栏更新了\r\n",
                    'keyword1' => "《$column[title]》",
                    'keyword2' => '更新',
                    'keyword3' => "《$article[title]》",
                    'url' => $url,
                    'remark' => "\r\n点击详情查看更新"
                ];
                $sender->send($data, $openId);
                if ($gap && (++$count & $num == 0)) {
                    sleep($gap);
                }
            }
        }
    }

    public function sendReview($lessonSn, $alt = true, $reason = '')
    {
        if (!$alt) {
            $msg = '未通过';
            $first = "您的课程审核未通过\r\n";

            if ($reason) {
                $msg = '未通过';
            }
        } else {
            $msg = '审核通过';
            $first = "您的课程审核已通过\r\n";
        }
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['tuid', 'title']);
        $openId = servUser::sole($this->platform)->uid2info($info['tuid'], 'openid')['openid'];
        $sender = new WechatTpl('mp');

        $data = array(
            'channel' => 'review_notice',
            'first' => $first,
            'project' => '课程《' . $info['title'] . '》',
            'result' => $msg,
            'time' => date('Y-m-d H:i:s'),
            'url' => $alt ? 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-lesson?lesson_sn=' . $lessonSn : '',
            'remark' => $alt ? ($alt === true ? "课程信息已更新，点击详情查看课程" : '') : ($reason ? "未通过原因是{$reason}，请修改后再次提交。" : "请修改后再次提交。"),
        );

        return $sender->send($data, $openId);
    }

    public function sendSeriesReview($seriesSn, $alt = true, $reason = '')
    {
        if (!$alt) {
            $msg = '未通过';
            $first = "您的系列课审核未通过\r\n";

            if ($reason) {
                $msg = '未通过';
            }
        } else {
            $msg = '审核通过';
            $first = "您的系列课审核已通过\r\n";
        }
        $info = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $seriesSn], ['uid', 'name']);
        $openId = servUser::sole($this->platform)->uid2info($info['uid'], 'openid')['openid'];
        $sender = new WechatTpl('mp');

        $data = array(
            'channel' => 'review_notice',
            'first' => $first,
            'project' => '系列课《' . $info['name'] . '》',
            'result' => $msg,
            'time' => date('Y-m-d H:i:s'),
            'url' => $alt ? 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-series?series_sn=' . $seriesSn : '',
            'remark' => $alt ? ($alt === true ? "系列课信息已更新，点击详情查看课程" : '') : ($reason ? "未通过原因是{$reason}，请修改后再次提交。" : "请修改后再次提交。"),
        );

        return $sender->send($data, $openId);
    }

    public function sendFeedback($ticketId)
    {
        $info = dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], '*');
        if ($info) {
            $openId = servUser::sole($this->platform)->uid2info($info['uid'], 'openid')['openid'];
            $sender = new WechatTpl('mp');

            $data = array(
                'channel' => 'feedback_notice',
                'first' => '你的反馈与建议已有处理结果。',
                'ticket' => json_decode($info['content'], true)['text'],
                'reply' => $info['remark'],
                'url' => '',
                'remark' => '谢谢你的关注与支持，如还有疑问，请在建议与反馈页继续反馈。',
            );
            return $sender->send($data, $openId);
        }
        return false;
    }

    public function sendChangeNotice($lessonSn, $reason, $remark, $url = '')
    {
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'plan']);
        $info['plan'] = json_decode($info['plan'], true);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);
        foreach ($lastEvents as $lastEvent) {
            if ($lastEvent['i_event'] == dataLessonAccess::EVENT_ENROLL) {
                $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                $sender = new WechatTpl('mp');
                $data = array(
                    'channel' => 'change_notice',
                    'first' => '你报名的以下课程发生变更。',
                    'keyword1' => '《' . $info['title'] . '》',
                    'keyword2' => $reason,
                    'url' => $url,
                    'remark' => "\r\n" . $remark,
                );
                $sender->send($data, $openId);
            }
        }
    }

    //课程返现通知
    public function sendCashBackNotice($lessonSn, $uid, $amount)
    {
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['price', 'title']);
        $info['plan'] = json_decode($info['plan'], true);

        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'cash_back_notice',
            'first' => '恭喜您获得课程返券',
            'keyword1' => '《' . $info['title'] . '》报名费返券',
            'keyword2' => '￥' . $amount,
            'keyword3' => '易灵微课账户余额',
            'url' => '',
            'remark' => "\r\n返券可抵扣任意课程报名费，但不可提现",
        );
        $sender->send($data, $openId);
    }

    //提现到账通知
    public function sendEnchashmentNotice($uid, $amount)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'enchashment_notice',
            'first' => '易灵微课自助提现',
            'money' => '￥' . $amount,
            'timet' => date('Y-m-d H:i'),
            'url' => '',
            'remark' => "资金已存入您的微信零钱",
        );
        $sender->send($data, $openId);

    }

    //开课前一个小时提醒
    public function beforeOneHourNotice($lessonSn)
    {
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'plan']);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $info['tuid']], 'name', 0);

        $info['plan'] = json_decode($info['plan'], true);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);

        $time = date('m月d日 H:i', strtotime($info['plan']['dtm_start']));
//        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-lesson?lesson_sn=' . $lessonSn;
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', '_') . '/lesson/course/'. $lessonSn;
        foreach ($lastEvents as $lastEvent) {
            if ($lastEvent['i_event'] == dataLessonAccess::EVENT_ENROLL) {
                //判断是否设置课前提醒
                $preclassSetting = servUser::sole($this->platform)->uid2setting($lastEvent['uid'], dataUser::NOTICE_PRECLASS);
                if (!$preclassSetting) {
                    continue;
                }
                $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                $sender = new WechatTpl('mp');
                $data = array(
                    'channel' => 'listen_notice',
                    'first' => '您报名的课程将在1小时后开始',
                    'keyword1' => '《' . $info['title'] . '》',
                    'keyword2' => '易灵微课',
                    'keyword3' => $teacherName,
                    'keyword4' => $time,
                    'url' => $url,
                    'remark' => "\r\n点击详情查看课程内容",
                );
                $sender->send($data, $openId);
            }
        }
    }

    //未听课提醒
    public function lessonOpenRemind($lessonSn, $past, $left)
    {
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'plan']);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $info['tuid']], 'name', 0);
        $info['plan'] = json_decode($info['plan'], true);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);
        $domain = config::load('boot', 'public', 'domain');
        $url = 'https://' . $domain . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D&&event=remind&mark={$past}hour#/";
        $time = date('m月d日 H:i', strtotime($info['plan']['dtm_start']));
        $pastStr = $past > 24 ? round($past / 24) . '天' : $past . '小时';
        $leftStr = $left > 24 ? round($left / 24) . '天' : $left . '小时';

        foreach ($lastEvents as $lastEvent) {
            if ($lastEvent['i_event'] == dataLessonAccess::EVENT_ENROLL) {
                //判断是否设置自动退款
                $autoRefundSetting = servUser::sole($this->platform)->uid2setting($lastEvent['uid'], dataUser::AUTO_REFUND);
                if (!$autoRefundSetting) {
                    continue;
                }

                $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                $sender = new WechatTpl('mp');
                $data = array(
                    'channel' => 'listen_notice',
                    'first' => "您报名的课程已开始{$pastStr}了，是否太忙忘记了呢？\r\n",
                    'keyword1' => '《' . $info['title'] . '》',
                    'keyword2' => '易灵微课',
                    'keyword3' => $teacherName,
                    'keyword4' => $time,
                    'url' => $url,
                    'remark' => "\r\n为保障您的权益，若{$leftStr}后仍未听课，我们将退还您的报名费。\r\n\r\n点击详情观看课程回放",
                );
                $sender->send($data, $openId);
            }
        }
    }

    public function todoRemind($uid, $day, $list)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $domain = config::load('boot', 'public', 'domain');
        $url = "https://$domain/my/lesson?mark=remind$day";
        $data = [
            'channel' => 'lesson_progress',
            'first' => "您已经 $day 天没有学习了，还有这些课程未完成哦",
            'keyword1' => '《' . implode('》《', $list) . '》',
            'keyword2' => '待学习',
            'keyword3' => '已开课',
            'url' => $url,
            'remark' => "\r\n为保障您的权益，若连续30天没有继续学习，我们将退还您的报名费。\r\n\r\n点击详情查看未完成课程"
        ];
        $sender = new WechatTpl('mp');
        $sender->send($data, $openId);
    }

    public function callNotice($first, $name, $time, $telephone, $openIds=[], $url='')
    {
        $openIds = array_merge(
            $openIds,
            \config::load('option', 'manager', 'openIds', [], 'Admin')
        );
        foreach ($openIds as $openId) {
            $sender = new WechatTpl('mp');
            $data = array(
                'channel' => 'call_notice',
                'first' => $first,
                'keyword1' => $name,
                'keyword2' => $time,
                'keyword3' => $telephone,
                'url' => $url,
                'remark' => "",
            );
            $res = $sender->send($data, $openId);
        }
    }

    // 逐字稿
    public function scriptNotice($send = false, $num = 10, $gap = 1)
    {
        $lessons = ['L5a02a65fb4995', 'L5a02a969a1d46', 'L59ffe3e32d3c3', 'L5a030660af352'];

        $time = date('Y年m月d日 H:i', time());
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/lesson-script_sc';
        $teacherName = '亦仁';
        $uids = [];
        foreach ($lessons as $lesson) {
            $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lesson], ['id', 'tuid', 'title', 'plan']);

            $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);
            $count = 0;
            foreach ($lastEvents as $lastEvent) {
                if (!array_key_exists($lastEvent['uid'], $uids)) {
                    if ($lastEvent['i_event'] >= dataLessonAccess::EVENT_ENROLL) {
                        $openId = servUser::sole($this->platform)->uid2info($lastEvent['uid'], 'openid')['openid'];
                        $sender = new WechatTpl('mp');
                        $data = array(
                            'channel' => 'listen_notice',
                            'first' => '讲师已提供文字版本的课程内容。',
                            'keyword1' => '《生财有术系列课第一期》',
                            'keyword2' => '易灵微课',
                            'keyword3' => $teacherName,
                            'keyword4' => $time,
                            'url' => $url,
                            'remark' => "\r\n点击详情查看文字版本课程内容。",
                        );
                        if ($send) {
                            $sender->send($data, $openId);
                        } else {
                            echo $lesson . ':' . $lastEvent['uid'] . '<br>';
                        }
                        $uids[$lastEvent['uid']] = 1;
                        if ($gap && (++$count % $num == 0)) {
                            sleep($gap);
                        }
                    }
                }

            }
        }

    }

    // 逐字稿
    public function scriptNotice2($lessonSn, $num = 10, $gap = 1)
    {
        $info = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'plan']);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $info['tuid']], 'name', 0);

        $info['plan'] = json_decode($info['plan'], true);
        $lastEvents = dataLessonAccess::sole($this->platform)->lastAccessEventsByLesson($info['id']);

        $time = date('Y年m月d日 H:i', time());
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/lesson-script_sc';
        $count = 0;
        $uids = [2, 3, 4, 6, 7];
        foreach ($uids as $uid) {
            $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
            $sender = new WechatTpl('mp');
            $data = array(
                'channel' => 'listen_notice',
                'first' => '讲师已提供文字版本的课程内容。',
                'keyword1' => '《' . $info['title'] . '》',
                'keyword2' => '易灵微课',
                'keyword3' => $teacherName,
                'keyword4' => $time,
                'url' => $url,
                'remark' => "\r\n点击详情查看文字版本课程内容。",
            );
            $sender->send($data, $openId);
            if ($gap && (++$count % $num == 0)) {
                sleep($gap);
            }
        }
    }

    public function toIncomeSettlement($uid, $time, $changeMoney, $balance)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-userMoney';
        $data = array(
            'channel' => 'income_settlement_notice',
            'first' => '你好，你有新的余额变动',
            'keyword1' => $time,
            'keyword2' => ($changeMoney > 0 ? '+ ' : '- ') . '￥' . abs($changeMoney),
            'keyword3' => '￥' . $balance,
            'url' => $url,
            'remark' => "\r\n点击可前往提现\r\n\r\n回复『关闭结算提醒』不再接收资金变动提醒",
        );
        return $sender->send($data, $openId);
    }

    public function toUserNotice($uid, $first, $type, $time, $remark)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'to_user_notice',
            'first' => $first,
            'keyword1' => $type,
            'keyword2' => $time,
            'url' => '',
            'remark' => "\r\n" . $remark,
        );
        return $sender->send($data, $openId);

    }

    public function teacherReplyNotice($teacherName, $uid, $lessonTitle, $lessonSn, $text, $cursor)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $url = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-courseMessageDetail?lesson_sn=' . $lessonSn . '&cursor=' . $cursor;
        $anchor = servLessonBoard::sole($this->platform)->parseCursor($cursor);
        $the = dataLessonBoard::sole($this->platform)->fetchOne(['id' => $anchor['id']], '*');
        $text = json_decode($the['message'], true)['text'] ?? '';
        $text = $this->returnText($text);
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'listen_notice',
            'first' => '您的留言' . $text . '被讲师回复',
            'keyword1' => '《' . $lessonTitle . '》',
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => date('Y-m-d H:i'),
            'url' => $url,
            'remark' => "\r\n点击详情查看回复",
        );
        return $sender->send($data, $openId);

    }

    protected function returnText($text)
    {
        if (mb_strlen($text, 'utf-8') > 10) {
            return '"' . mb_substr($text, 0, 10, 'utf-8') . '..."';
        } elseif (!empty($text)) {
            return '"' . $text . '"';
        } else {
            return '';
        }

    }

    public function boardNotify($uid, $speakerName, $teacherName, $lessonTitle, $url)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'listen_notice',
            'first' => "$speakerName 回复了您的留言",
            'keyword1' => $lessonTitle,
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => date('Y-m-d H:i:s'),
            'url' => $url,
            'remark' => "\r\n回复【关闭留言通知】，不再接收此类提醒",
        );
        $sender->send($data, $openId);
    }

    public function boardNotify2Student($count, $uid, $teacherName, $lessonTitle, $url)
    {

        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'listen_notice',
            'first' => '您的留言今日有' . $count . '条新的回复',
            'keyword1' => $lessonTitle,
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => date('Y-m-d'),
            'url' => $url,
            'remark' => "\r\n回复【关闭留言通知】，不再接收此类提醒",
        );
        $sender->send($data, $openId);
    }

    public function boardNotify2Teacher($count, $uid, $teacherName, $lessonTitle, $url)
    {

        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'listen_notice',
            'first' => '您的课程今日有' . $count . '条新留言',
            'keyword1' => $lessonTitle,
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => date('Y-m-d'),
            'url' => $url,
            'remark' => "\r\n回复【关闭留言通知】，不再接收此类提醒",
        );
        $sender->send($data, $openId);
    }


    //提示获得优惠券
    public function getReward($uid, $teacherName, $lessonTitle, $price, $psn)
    {
        $domain = 'https://' . \config::load('boot', 'public', 'domain', '', 'Student');
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        $data = array(
            'channel' => 'listen_notice',
            'first' => '您已报名成功，并且获得三张' . $price . '元全额优惠券',
            'keyword1' => '《' . $lessonTitle . '》',
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => date('Y年m月d日 H:i'),
            'url' => $domain . '/promote-receive?sn=' . $psn,
            'remark' => "\r\n点击详情分享优惠券给朋友。",
        );
        $sender->send($data, $openId);
    }

    public function send($uid, $data)
    {
        $openId = servUser::sole($this->platform)->uid2info($uid, 'openid')['openid'];
        $sender = new WechatTpl('mp');
        return $sender->send($data, $openId);
    }
}