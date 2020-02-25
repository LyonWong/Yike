<?php


namespace _;

use Core\unitInstance;

class servNotify extends serv_
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

    public function incomeNotify($tmsStart, $tmsEnd)
    {
        $moneyList = servMoney::sole($this->platform)->moneyListByTime($tmsStart, $tmsEnd);
        foreach ($moneyList as $uid => $items) {
            $amount = 0;
            foreach ($items as $item) {
                $amount += $item['amount'];
            }
            if ($amount > 0) {
                //判断是否设置结算提醒
                $noticePayoffSetting = servUser::sole(null)->uid2setting($uid, dataUser::NOTICE_PAYOFF);
                if ($noticePayoffSetting) {
                    $balance = servUserKeep::sole($this->platform)->getBalance($uid);
                    servMpMsg::sole(null)->toIncomeSettlement($uid, date('Y-m-d H:i'), $amount / 100, $balance / 100);
                }
            }
        }

    }

    public function boardReplyNotify($uid, $uid_, $lessonId, $target)
    {
        //判断是否设置回复通知
        if ( !servUser::sole($this->platform)->uid2setting($uid, dataUser::NOTICE_BOARD)) {
            return false;
        }
        $lesson = servLesson::sole($this->platform)->id2info($lessonId, ['sn', 'title', 'tuid']);
//        var_dump ($lesson);
        $user_ = servUser::sole($this->platform)->uid2profile($uid_);
        $teacher = servUser::sole($this->platform)->uid2profile($lesson['tuid']);
        $domain = config::load('boot', 'public', 'domain');
        $url = "https://$domain/study/board-detail?mode=refer&sn=$lesson[sn]&target=$target";
        servMpMsg::sole($this->platform)->boardNotify($uid, $user_['name'], $teacher['name'], $lesson['title'], $url);
    }

    public function boardTeacherNotify($tmsStart, $tmsEnd)
    {
        $list = servLessonBoard::sole($this->platform)->boardLessonListByTime($tmsStart, $tmsEnd);
        $domain = config::load('boot', 'public', 'domain');
        foreach ($list as $lessonId => $items) {
            $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], ['sn', 'tuid', 'title']);
            $teacher = servUser::sole($this->platform)->uid2profile($lesson['tuid']);
            $count = 0;
            foreach ($items as $item) {
                if ($item['uid'] == $lesson['tuid']) {
                    continue; // 跳过讲师自己的回复
                }
                $count++;
            }
            if ($count > 0) {
                $url = "https://$domain/study/board/argue?sn=$lesson[sn]";
                servMpMsg::sole($this->platform)->boardNotify2Teacher($count, $lesson['tuid'], $teacher['name'], $lesson['title'], $url);
            }
        }
    }

    public function board2student($tmsStart, $tmsEnd)
    {

        $list = servLessonBoard::sole($this->platform)->boardListByTime($tmsStart, $tmsEnd);
        foreach ($list as $uid => $items) {

            //判断是否设置回复通知
            $replySetting = servUser::sole($this->platform)->uid2setting($uid, dataUser::NOTICE_BOARD);
            if (!$replySetting) {
                continue;
            }
            $countItems = count($items);
            $teacherNames = '';
            $titles = '';

            //取部分课程昵称
            $lessonIds = array_unique(array_column($items, 'lesson_id'));
            $countLessonIds = count($lessonIds);
            if ($countLessonIds > 3) {
                $lessonIds = array_slice($lessonIds, 0, 3);
            }
            foreach ($lessonIds as $lessonId) {
                $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], ['tuid', 'title']);
                $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $lesson['tuid']], 'name', 'name');
                if (empty($titles)) {
                    $titles = '《' . $lesson['title'] . '》';
                } else {
                    $titles = $titles . '《' . $lesson['title'] . '》';
                }
                if (empty($teacherNames)) {
                    $teacherNames = $teacherName;
                } elseif (stripos($teacherNames, $teacherName) === false) {
                    $teacherNames = $teacherNames . '、' . $teacherName;
                }
            }
            $url = $this->buildBoardMspUrl($countLessonIds, $countItems, $items);
            servMpMsg::sole($this->platform)->boardNotify2Student($countItems, $uid, $teacherNames, $titles, $url);
        }


    }

    protected function buildBoardMspUrl($countLessonIds, $countItems, $items)
    {
        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
        if ($countLessonIds === 1) {
            $lessonSn = servLesson::sole($this->platform)->id2sn($items[0]['lesson_id']);
            if ($countItems === 1) {
                $cursor = servLessonBoard::sole($this->platform)->id2cursor($items[0]['id']);
                $url = 'https://' . $domain . '/weixin-courseMessageDetail?lesson_sn=' . $lessonSn . '&cursor=' . $cursor;

            } else {

                $url = 'https://' . $domain . '/weixin-courseMessage?lesson_sn=' . $lessonSn;

            }
        } else {
            $url = 'https://' . $domain . '/weixin-enrolled';
        }

        return $url;

    }

    public function board2teacher($tmsStart, $tmsEnd)
    {

        $list = servLessonBoard::sole($this->platform)->boardLessonListByTime($tmsStart, $tmsEnd);
        $teacherList = [];
        foreach ($list as $lessonId => $items) {
            $tuid = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'tuid', 'tuid');
            foreach ($items as $item) {
                if ($item['uid'] == $tuid) {
                    continue; // 跳过讲师自己的回复
                }
                $teacherList[$tuid][] = $item;
            }
        }
        foreach ($teacherList as $tuid => $items) {
            //判断是否设置回复通知
            $replySetting = servUser::sole($this->platform)->uid2setting($tuid, dataUser::NOTICE_BOARD);
            if (!$replySetting) {
                continue;
            }

            $countItems = count($items);
            $teacher = dataUser::sole($this->platform)->fetchOne(['id' => $tuid], ['name', 'sn']);
            $titles = '';
            $lessonIds = array_unique(array_column($items, 'lesson_id'));
            $countLessonIds = count($lessonIds);
            if ($countLessonIds > 3) {
                $lessonIds = array_slice($lessonIds, 0, 3);
            }
            foreach ($lessonIds as $lessonId) {
                $title = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'title', 'title');
                if (empty($titles)) {
                    $titles = '《' . $title . '》';
                } else {
                    $titles = $titles . '《' . $title . '》';
                }
            }
            $domain = \config::load('boot', 'public', 'domain', '', 'Student');
            if ($countLessonIds === 1) {
                $lessonSn = servLesson::sole($this->platform)->id2sn($items[0]['lesson_id']);
                if ($countItems === 1) {
                    $cursor = servLessonBoard::sole($this->platform)->id2cursor($items[0]['id']);
                    $url = 'https://' . $domain . '/weixin-courseMessageDetail?lesson_sn=' . $lessonSn . '&cursor=' . $cursor;

                } else {

                    $url = 'https://' . $domain . '/weixin-courseMessage?lesson_sn=' . $lessonSn;

                }
            } else {
                $url = 'https://' . $domain . '/weixin-teacherCourse?tusn=' . $teacher['sn'];
            }

            servMpMsg::sole($this->platform)->boardNotify2Teacher($countItems, $tuid, $teacher['name'], $titles, $url);
        }

    }
}