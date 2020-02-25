<?php


namespace _;


use Core\unitInstance;

class servTrigger extends serv_
{
    use unitInstance;

    const TAG_LESSON_UPCOME = 'lesson-upcome'; // 讲师开课提醒
    const TAG_LESSON_START = 'lesson-start'; // 授课开始
    const TAG_LESSON_PAUSE = 'lesson-pause'; // 授课暂停
    const TAG_LESSON_ABORT = 'lesson-abort'; // 授课异常终止
    const TAG_LESSON_DELAY = 'lesson-delay'; // 讲师未按时开课
    const TAG_LESSON_SILENT = 'lesson-silent'; // 讲师沉默提醒
    const TAG_LESSON_FINISH = 'lesson-finish'; // 课程结束
    const TAG_REFUND_AUTO = 'refund-auto'; // 未听课自动退款
    const TAG_REFUND_REMIND = 'refund-remind'; // 退款提醒
    const TAG_REFUND_LAPSE = 'refund-lapse'; // 限时退款到期
    const TAG_LESSON_UP2STUDENT = 'lesson-up2student'; //开课前1小时提醒
    const TAG_LESSON_FINISH2NOTIN = 'lesson-finish2notin';//课程结束12小时未听课


    const COUNTDOWN = [
        self::TAG_LESSON_START => SECONDS_MINUTE,
        self::TAG_LESSON_PAUSE => SECONDS_MINUTE * 15,
        self::TAG_LESSON_SILENT => SECONDS_MINUTE * 30,
        self::TAG_LESSON_ABORT => SECONDS_MINUTE * 60,
        self::TAG_LESSON_FINISH => SECONDS_DAY * 3,
        self::TAG_REFUND_REMIND => SECONDS_MINUTE * 55,
        self::TAG_REFUND_LAPSE => SECONDS_HOUR,
        self::TAG_REFUND_AUTO => SECONDS_DAY * 7,
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function makeKey($tag, array $args = [])
    {
        $args['_platform_'] = $this->platform;
        asort($args);
        $key = "$tag:" . http_build_query($args);
        return $key;
    }

    /**
     * 重置触发
     * @param $tag
     * @param array $args
     * @param null $timeout
     * @return bool
     */
    public function reset($tag, array $args = [], $timeout = null)
    {
        $key = $this->makeKey($tag, $args);
        if ($timeout) {
            $expire = $timeout;
        } else {
            $expire = self::COUNTDOWN[$tag] ?? null;
        }
        if ($expire > 0) {
            return data::redis('notify')->setex($key, $expire, 1);
        } else {
            return $this->expire($key);
        }
    }

    /**
     * 只触发一次
     * @param $tag
     * @param array $args
     * @param null $timeout
     * @return bool
     */
    public function touch($tag, array $args = [], $timeout = null)
    {
        $key = $this->makeKey($tag, $args);
        if ($timeout) {
            $expire = $timeout;
        } else {
            $expire = self::COUNTDOWN[$tag] ?? null;
        }
        if ($expire > 0) {
            $res = data::redis('notify')->setnx($key, 1);
            if ($res) {
                data::redis('notify')->expire($key, $expire);
            }
            return true;
        } else {
            return false;
        }
    }

    public function cancel($tag, array $args = [])
    {
        $key = $this->makeKey($tag, $args);
        $this->expire($key);
    }


    public function list()
    {
        $list = [];
        $keys = data::redis('notify')->keys('*');
        foreach ($keys as $key) {
            list($tag, $str) = explode(':', $key);
            parse_str($str, $args);
            $list[] = [
                'key' => $key,
                'tag' => $tag,
                'args' => $args,
                'info' => $this->info($args),
                'ttl' => data::redis('notify')->ttl($key),
            ];
        }
        return $list;
    }

    public function expire($key, $ttl = 0)
    {
        if ($ttl > 0) {
            return data::redis('notify')->expire($key, $ttl);
        } else {
            return data::redis('notify')->del($key);
        }
    }

    public function info($args)
    {
        $info = [];
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'uid':
                    $info[$key] = servUser::sole($this->platform)->uid2profile($val)['name'];
                    break;
                case 'lesson_id':
                    $info[$key] = servLesson::sole($this->platform)->profile($val, 'id')['title'];
                    break;
                case 'lesson_sn':
                    $info[$key] = servLesson::sole($this->platform)->profile($val)['title'];
                    break;
            }
        }
        return $info;
    }

    /**
     * 开课时间设置、变更
     * @param $lessonSn
     * @param $dtmStart
     */
    public function onLessonStartSet($lessonSn, $dtmStart)
    {
        $countdown = strtotime($dtmStart) - time();
        $info = servLesson::sole($this->platform)->sn2info($lessonSn, ['i_form']);
        // 开课前15分钟提醒
        $this->reset(servTrigger::TAG_LESSON_UPCOME,
            ['lesson_sn' => $lessonSn,],
            $countdown - SECONDS_MINUTE * 15
        );
        if ($info['i_form'] == dataLesson::FORM_IM) { // 直播课倒计时
            // 迟到15分钟开课
            $this->reset(servTrigger::TAG_LESSON_DELAY,
                ['lesson_sn' => $lessonSn,],
                $countdown + SECONDS_MINUTE * 15);
            // 课前一小时提醒
            $this->reset(servTrigger::TAG_LESSON_UP2STUDENT,
                ['lesson_sn' => $lessonSn,],
                $countdown - SECONDS_HOUR);
        } else {
            $this->cancel(servTrigger::TAG_LESSON_DELAY,
                ['lesson_sn' => $lessonSn,]);
            $this->cancel(servTrigger::TAG_LESSON_UP2STUDENT,
                ['lesson_sn' => $lessonSn,]);
        }
    }

    /**
     * 课程开启
     * @param $lessonSn
     */
    public function onLessonOpen($lessonSn)
    {
        $this->touch(servTrigger::TAG_LESSON_START, ['lesson_sn' => $lessonSn]);
//        $this->touch(servTrigger::TAG_REFUND_AUTO, ['lesson_sn' => $lessonSn]);
        $this->touch(servTrigger::TAG_LESSON_ABORT, ['lesson_sn' => $lessonSn]);
//        $this->touch(servTrigger::TAG_LESSON_FINISH2NOTIN, ['lesson_sn' => $lessonSn, 'past' => 72, 'left' => 72], SECONDS_HOUR * 72);
        $this->cancel(servTrigger::TAG_LESSON_DELAY, ['lesson_sn' => $lessonSn]);
    }

    /**
     * 课程正在直播(讲师发言)
     * @param $lessonSn
     */
    public function onLessonLive($lessonSn)
    {
        $this->reset(servTrigger::TAG_LESSON_PAUSE, ['lesson_sn' => $lessonSn]);
        $this->reset(servTrigger::TAG_LESSON_SILENT, ['lesson_sn' => $lessonSn]);
        $this->reset(servTrigger::TAG_LESSON_FINISH, ['lesson_sn' => $lessonSn], SECONDS_HOUR);
        $this->cancel(servTrigger::TAG_LESSON_ABORT, ['lesson_sn' => $lessonSn]);
    }

    public function offLessonLive($lessonSn)
    {
        $this->cancel(servTrigger::TAG_LESSON_PAUSE, ['lesson_sn' => $lessonSn]);
        $this->cancel(servTrigger::TAG_LESSON_SILENT, ['lesson_sn' => $lessonSn]);
        $this->cancel(servTrigger::TAG_LESSON_ABORT, ['lesson_sn' => $lessonSn]);
    }

    /**
     * 课程进入交流状态
     * @param $lessonSn
     */
    public function onLessonRepose($lessonSn)
    {
        $this->reset(servTrigger::TAG_LESSON_FINISH, ['lesson_sn' => $lessonSn]);
        $this->offLessonLive($lessonSn);
    }

    /**
     * 课程结束
     * @param $lessonSn
     */
    public function onLessonFinish($lessonSn)
    {
        $this->offLessonLive($lessonSn);
    }

    /**
     * 学员进入课堂
     * @param $lessonSn
     * @param $uid
     */
    public function onLessonFirstEntry($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $paidStatus = dataOrder::sole($this->platform)->fetchOne([
            'uid' => $uid,
            'lesson_id' => $lessonId,
            'i_status > 0',
        ], 'i_status', 0);
        if ($paidStatus == dataOrder::STATUS_PAID) {
            $this->touch(servTrigger::TAG_REFUND_REMIND, ['uid' => $uid, 'lesson_sn' => $lessonSn]);
            $this->touch(servTrigger::TAG_REFUND_LAPSE, ['uid' => $uid, 'lesson_sn' => $lessonSn]);
        }
    }

    /**
     * 课程状态变更
     * @param $lessonSn
     * @param $toStep
     */
    public function onLessonStepChange($lessonSn, $toStep)
    {
    }
}