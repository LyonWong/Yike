<?php


namespace _\cron;


use _\dataLesson;
use _\dataLessonAccess;
use _\dataOrder;
use _\dataUser;
use _\servLesson;
use _\servLessonSeries;
use _\servMpMsg;
use _\servRefund;
use _\servUser;
use Core\unitFile;

class ctrlRefund extends ctrl_
{
    use unitFile;

    const DAY_REFUND = 30; // 自动退款期限
    const DAYS_REMIND = [10, 20]; // 未听课提醒间隔
    const DEADLINE = 300; // 超过300天强制退款

    protected $lessonDict;

    protected $seriesDict;

    public function _DO_()
    {
        $todolist = $this->todolist();
        foreach ($todolist as $_key => $_item) {
            list($_uid) = explode('|', $_key);
            if (in_array($_item['days'], self::DAYS_REMIND)) {
                $this->remind($_uid, $_item['days'], $_item['list']);
            }
            $autoRefund = servUser::sole($this->platform)->uid2setting($_uid, dataUser::AUTO_REFUND); //判断是否设置自动退
            if ($autoRefund && $_item['days'] >= self::DAY_REFUND) {
                $this->refund($_uid, $_item['list']);
            } else if ($_item['days'] >= self::DEADLINE) {
                $this->refund($_uid, $_item['list']);
            }
        }
    }

    public function _DO_check($days)
    {
        $todolist = $this->todolist();
        foreach ($todolist as $_key => $_item) {
            list($_uid) = explode('|', $_key);
            if ($_item['days'] > $days) {
                $this->check($_uid, $_item['list']);
            }
        }
    }

    public function _DO_remind($days)
    {
        $todolist = $this->todolist();
        foreach ($todolist as $_key => $_item) {
            list($_uid) = explode('|', $_key);
            if ($_item['days'] > $days) {
                $this->remind($_uid, $days, $_item['list']);
            }
        }
    }

    public function _DO_refund($days)
    {
        $todolist = $this->todolist();
        foreach ($todolist as $_key => $_item) {
            list($_uid) = explode('|', $_key);
            if ($_item['days'] > $days) {
                $this->refund($_uid, $_item['list']);
            }
        }
    }

    public function todolist()
    {
        $daoAccess = dataLessonAccess::sole($this->platform);

        $rem = 'refund_access_id';
        $id = $this->fileCheck($rem) ? ($this->fileRead($rem) ?: 0) : 0;
        $step = 10000;
        $todo = [];
        $today = date('Y-m-d');
        $todms = strtotime($today);
        do {
            $res = $daoAccess->fetchAll(["id>$id", 'lesson_id<>0'], '*', null, null, "limit $step");
            foreach ($res as $row) {
                $_lid = $row['lesson_id'];
                $_eid = $row['i_event'];

                $_dict = $this->dict($_lid);

                if (!$_dict['time']) {
                    continue; // 未开课跳过
                }

                $_key = "$row[uid]|$_dict[tsn]";
                //以开课时间和事件时间的较大者作为活跃日期
                $_days = ($todms - strtotime(strToDate(max($row['tms'], $_dict['time']), 'Y-m-d'))) / SECONDS_DAY;

                if ($_eid == dataLessonAccess::EVENT_ENROLL) { // 添加待学习课程
                    $todo[$_key]['list'][$_lid] = $_eid;
                }
                if (in_array($_eid, [
                    dataLessonAccess::EVENT_ACCESS,
                    dataLessonAccess::EVENT_CONFIRM,
                    dataLessonAccess::EVENT_RESET,
                    dataLessonAccess::EVENT_REFUND
                ])) { // 移除已听课程
                    unset($todo[$_key]['list'][$_lid]);
                    if ($_days < self::DAYS_REMIND[0]) { // 排除近期有活跃
                        unset($todo[$_key]);
                    }
                }

                if (empty($todo[$_key]['list'])) { // 若无未听课程，清除记录
                    unset($todo[$_key]);
                } else { // 更新活跃天数
                    $todo[$_key]['days'] = $_days;
                }

                if (empty($todo)) { // 若todolist被完全清空，记录ID位置
                    $this->fileWrite($rem, $row['id']);
                }

            }
            $end = end($res);
            $id = $end['id'] ?? $id;
        } while (count($res) == $step);
        return $todo;
    }

    public function check($uid, $lessons)
    {
        $daoOrder = dataOrder::sole($this->platform);
        foreach ($lessons as $lid => &$val) {
            $_order = $daoOrder->fetchOne([
                'uid' => $uid,
                'lesson_id' => $lid,
                'order_amount>0',
                'i_status' => dataOrder::STATUS_PAID
            ], ['sn']);
            if (!$_order) { // 去掉没有可退款订单的课程
                unset($lessons[$lid]);
            } else {
                $val = $this->dict($lid)['title'];
            }
        }
        if (empty($lessons)) {
            return null;
        }
        $list = $this->merge($lessons);
        $content = [
            'uid' => $uid,
            'lessons' => $lessons,
            'check' => array_column($list, 'name', 'tsn'),
        ];
        \output::debug('refund_check', $content, DEBUG_SLOT_NOTE, DEBUG_REPORT_STD);
        return $list;
    }

    public function remind($uid, $day, $lessons)
    {
//        $daoOrder = dataOrder::sole($this->platform);
//        $daoAccess = dataLessonAccess::sole($this->platform);
        foreach ($lessons as $lid => &$val) {
            $val = $this->dict($lid)['title'];
        }
        if (empty($lessons)) {
            return null;
        }
        $list = $this->merge($lessons);
        servMpMsg::sole($this->platform)->todoRemind($uid, $day, array_column($list, 'name'));
        $content = [
            'uid' => $uid,
            'lessons' => $lessons,
            'remind' => array_column($list, 'name', 'tsn')
        ];
        \output::debug('refund_remind', $content, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        return $list;
    }

    public function refund($uid, $lessons)
    {
        $daoOrder = dataOrder::sole($this->platform);
        $daoAccess = dataLessonAccess::sole($this->platform);
        foreach ($lessons as $lid => &$val) {
            $_order = $daoOrder->fetchOne([
                'uid' => $uid,
                'lesson_id' => $lid,
                'order_amount>0',
                'i_status' => dataOrder::STATUS_PAID
            ], ['sn']);
            if (!$_order) { // 去掉没有可退款订单的课程
                unset($lessons[$lid]);
                // 增加确认事件
                $daoAccess->append($lid, $uid, dataLessonAccess::EVENT_CONFIRM, ["mark" => "refund"]);
            } else {
                $val = $this->dict($lid)['title'];
            }
        }
        if (empty($lessons)) {
            return null;
        }
        $list = $this->merge($lessons);
        $srvRefund = servRefund::sole($this->platform);
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        $srvMsg = servMpMsg::sole($this->platform);
        $reason = self::DAY_REFUND . "天未听课自动退款";
        foreach ($list as $tsn => $info) {
            if ($info['type'] == 'lesson') {
                $vars = $srvRefund->refundLesson($tsn, $usn, $reason);
            }
            if ($info['type'] == 'series') {
                $vars = $srvRefund->refundSeries($tsn, $usn, $reason);
            }
            if (!empty($vars)) {
                $refundMoney = [];
                foreach ($vars as $iWay => $item) {
                    if ($iWay) {
                        $refundMoney['weixin'] = ($refundMoney['weixin'] ?? 0) + $item / 100;
                    } else {
                        $refundMoney['balance'] = $item / 100;
                    }
                }
                $srvMsg->sendRefundMessage($tsn, $uid, $refundMoney, "您已经超过" . self::DAY_REFUND . "天没有听课了，为保障您的权益，我们已原路退还报名费");
            }
        }
        $content = [
            'uid' => $uid,
            'lessons' => $lessons,
            'refund' => array_column($list, 'name', 'tsn'),
        ];
        \output::debug('refund_done', $content, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        return $list;
    }

    public function merge($lessons)
    {
        $list = [];
        foreach ($lessons as $_lid => $_day) {
            $_dict = $this->dict($_lid);
            $list[$_dict['tsn']] = $_dict;
        }
        return $list;
    }

    public function dict($lessonId)
    {
        if (empty($this->lessonDict[$lessonId])) {
            $lesson = servLesson::sole($this->platform)->id2info($lessonId, ['sn', 'category', 'title', 'i_step', 'plan']);
            if ($tsn = $lesson['category']) {
                if (empty($this->seriesDict[$tsn])) {
                    $this->seriesDict[$tsn] = servLessonSeries::sole($this->platform)->sn2info($lesson['category'], ['name']);
                }
                $lesson['tsn'] = $tsn;
                $lesson['name'] = $this->seriesDict[$tsn]['name'];
                $lesson['type'] = 'series';
            } else {
                $lesson['tsn'] = $lesson['sn'];
                $lesson['name'] = $lesson['title'];
                $lesson['type'] = 'lesson';
            }
            $lesson['time'] = $lesson['i_step'] > dataLesson::STEP_OPENED ? $lesson['plan']['dtm_start'] : null;
            $this->lessonDict[$lessonId] = $lesson;
        }
        return $this->lessonDict[$lessonId];
    }

}