<?php


namespace _;


use Admin\servLessonAccess;
use Core\unitInstance;

class servLessonHub extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function slice($tag, $cursor, $limit)
    {
        $list = dataLessonHub::sole($this->platform)->sliceByTag($tag, $cursor, $limit);
        foreach ($list as &$item) {
            $item['profile'] = $this->profile($item['tsn']);
            $item['cursor'] = dataLessonHub::buildCursor($item);
            unset ($item['id']);
        }
        return $list;
    }

    public function search($query, $tags, $cursor, $limit)
    {
        $querys = explode(' ', $query);
        $list = dataLessonHub::sole($this->platform)->search($querys, $tags, $cursor, $limit);
        foreach ($list as &$item) {
            $item['cursor'] = dataLessonHub::buildCursor($item);
            unset($item['id']);
        }
        return $list;
    }

    public function latest($tag, $cursor, $limit)
    {
        $list = dataLessonHub::sole($this->platform)->latestByTag($tag, $cursor, $limit);
        foreach ($list as &$item) {
            $item['profile'] = $this->profile($item['tsn']);
            $item['cursor'] = dataLessonHub::buildCursor($item);
            unset ($item['id']);
        }
        return $list;
    }

    public function listBlock($block, $cursor, $limit)
    {
        $list = dataLessonHub::sole($this->platform)->sliceByTag(dataLessonHub::TAG_BLOCK.":$block", $cursor, $limit);
        return $list;
    }

    public function banners()
    {
        $list = $this->listBlock(servBlock::TYPE_MAP[dataBlock::TYPE_BANNER], '--', 0);
        $banners = [];
        foreach ($list as $item) {
            $target = $this->target($item['tsn']);
            $banners[] = [
                'tsn' => $item['tsn'],
                'cover' => $target['cover'],
            ];
        }
        return $banners;
    }

    public function profile($tsn)
    {
        if ($tsn[0] == data::SN_LESSON) {
            $profile = servLesson::sole($this->platform)->sn2profile($tsn);
            $profile['progress'] = dataLesson::sole($this->platform)->fetchOne(['category'=> $tsn, 'i_step>0', 'i_form>0'], "count(*)", 0);
        }
        if ($tsn[0] == data::SN_SERIES) {
            $profile = servLessonSeries::sole($this->platform)->sn2profile($tsn);
        }
        return $profile ?? null;
    }

    public function target($tsn)
    {
        if ($tsn[0] == data::SN_LESSON) {
            $res = dataLesson::sole($this->platform)->inquireOne(['sn' => $tsn], ['title', 'tuid']);
            $data = [
                'title' => $res['title'],
                'cover' => \view::upload(servLesson::sole($this->platform)->sn2cover($tsn)),
                'teacher' => servUser::sole($this->platform)->uid2profile($res['tuid']),
            ];
        }
        if ($tsn[0] == data::SN_SERIES) {
            $res = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $tsn], ['name', 'uid', 'introduce']);
            $data = [
                'title' => $res['name'],
                'cover' => \view::upload($res['introduce']['cover']),
                'teacher' => servUser::sole($this->platform)->uid2profile($res['uid'])
            ];
        }
        return $data ?? null;
    }

    public function own($uid)
    {
        $daoAccess = dataLessonAccess::sole($this->platform);
        $events = [
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_REFUND,
            dataLessonAccess::EVENT_RESET,
        ];
        $ownLessons = $daoAccess->ownLessons($uid, $events);
        $lessonIds = array_column($ownLessons, 'lesson_id');
        $lessons = dataLesson::sole($this->platform)->fetchByIDs($lessonIds, 'id,sn,category,title,i_form,i_step,extra->>"$.cover" as cover');
        $lessonDict = $seriesDict = [];
        foreach ($lessons as $l) {
            $lessonDict[$l['id']] = $l;
        }
        $seriesSNs = array_unique(array_column($lessons, 'category'));
        $series = dataLessonSeries::sole($this->platform)->fetchBySNs($seriesSNs, 'id,sn,name,introduce->>"$.cover" as cover');
        foreach ($series as $s) {
            $seriesDict[$s['sn']] = $s;
        }
        $list = [];
        foreach ($ownLessons as $row) {
            $event = servLessonAccess::ACCESS_MAP[$row['i_event']];
            $form = servLesson::FORM_MAP[$lessonDict[$row['lesson_id']]['i_form']];
            $step = servLesson::STEP_MAP[$lessonDict[$row['lesson_id']]['i_step']];
            switch ($form) {
                case 'article':
                    $_cn = $lessonDict[$row['lesson_id']]['category'];
                    if ($_cn) { // 从属文章
                        $list[$_cn]['events'][$event] = ($list[$_cn]['events'][$event] ?? 0) +1;
                        if ($event!='enroll') {
                            $list[$_cn]['events']['enroll'] = ($list[$_cn]['events']['enroll']??0) -1;
                        }
                    } else { // 独立文章
                        $_lesson = $lessonDict[$row['lesson_id']];
                        $_sn = $_lesson['sn'];
                        $list[$_sn] = [
                            'sn' => $_lesson['sn'],
                            'type' => 'lesson',
                            'title' => $_lesson['title'],
                            'cover' => \view::upload($_lesson['cover']),
                            'event' => $event,
                            'form' => $form,
                            'step' => $step,
                            'events' => [$event => 1],
                            'total' => 1
                        ];
                    }
                    break;
                case 'column':
                    $_lesson = $lessonDict[$row['lesson_id']];
                    $_sn = $_lesson['sn'];
                    $_total = dataLesson::sole($this->platform)->fetchOne(['category' => $_sn, 'i_step>0', 'i_form>0'], "count(*)", 0);
                    $list[$_sn] = arrayMergeForce($list[$_sn]??[], [
                        'sn' => $_lesson['sn'],
                        'type' => 'lesson',
                        'title' => $_lesson['title'],
                        'cover' => \view::upload($_lesson['cover']),
                        'form' => $form,
                        'step' => $step,
                        'total' => $_total,
                    ]);
                    $list[$_sn]['events']['enroll'] = ($list[$_sn]['events']['enroll']?? 0) + $_total;
                    break;
                default:
                    if ($lessonDict[$row['lesson_id']]['category']) { // 系列课
                        $_series = $seriesDict[$lessonDict[$row['lesson_id']]['category']];
                        $_sn = $_series['sn'];
                        if (empty($list[$_series['sn']])) {
                            $list[$_series['sn']] = [
                                'sn' => $_series['sn'],
                                'type' => 'series',
                                'title' => $_series['name'],
                                'cover' => \view::upload($_series['cover']),
                            ];
                        }
                        $list[$_sn]['steps'][$step] = ($list[$_sn]['steps'][$event] ?? 0) + 1;
                        $list[$_sn]['events'][$event] = ($list[$_sn]['events'][$event] ?? 0) + 1;
                    } else { // 单课
                        $_lesson = $lessonDict[$row['lesson_id']];
                        $_sn = $_lesson['sn'];
                        $list[$_sn] = [
                            'sn' => $_lesson['sn'],
                            'type' => 'lesson',
                            'title' => $_lesson['title'],
                            'cover' => \view::upload($_lesson['cover']),
                            'event' => $event,
                            'form' => $form,
                            'step' => $step,
                        ];
                        $list[$_sn]['events'][$event] = 1;
                    }
                    $list[$_sn]['total'] = ($list[$_sn]['total']??0) + 1;
                    break;
            }
        }
        return array_values($list);
    }

    public function owns($uid, $seriesSn)
    {
        $daoAccess = dataLessonAccess::sole($this->platform);
        $events = [
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_REFUND,
            dataLessonAccess::EVENT_RESET,
        ];
        $lessons = dataLesson::sole($this->platform)->fetchAll(['category' => $seriesSn], 'id,sn,title,i_form,i_step,plan->>"$.dtm_start" as dtm_start', 'id');
        $filter = [
            'lesson_id in (' . implode(',', array_keys($lessons)) . ')'
        ];
        $ownLessons = $daoAccess->ownLessons($uid, $events, $filter);
        $data = [];
        foreach ($ownLessons as $row) {
            $_lesson = $lessons[$row['lesson_id']];
            $_key = "$_lesson[dtm_start]|$_lesson[id]";
            $data[$_key] = [
                'sn' => $_lesson['sn'],
                'title' => $_lesson['title'],
                'event' => servLessonAccess::ACCESS_MAP[$row['i_event']],
                'form' => servLesson::FORM_MAP[$_lesson['i_form']],
                'step' => servLesson::STEP_MAP[$_lesson['i_step']],
            ];
        }
        ksort($data);
        return array_values($data);
    }

    // 公开可见的
    public function overt($tuid) {
        $where = [
            'tuid' => $tuid,
            'i_form>0',
            'i_step>0'
        ];
        $lessons = dataLesson::sole($this->platform)->fetchAll($where, ['sn', 'category'], 'sn', 'category', "order by id desc");
        $list = [];
        foreach ($lessons as $sn => $category) {
            if ($category) {
                if (empty ($list[$category])) {
                    $_profile = $this->profile($category);
                    if ( $_profile['type'] == 'lesson' && $_profile['show']) {
                        $list[$category] = $_profile;
                    }
                    if ( $_profile['type'] == 'series' && $_profile['status'] == 'opened') {
                        $list[$category] = $_profile;
                    }
                }
            } else {
                $list[$sn] = $this->profile($sn);
            }
        }
        return array_values($list);
    }

    // 讲师开设的
    public function created($tuid, $filter=[])
    {
        $where = array_merge(['tuid' => $tuid], $filter);
        $field = ['sn', 'category'];
        $lessons = dataLesson::sole($this->platform)->fetchAll($where, $field, 'sn', 'category', "order by id desc");
        $list = [];
        foreach ($lessons as $sn => $category) {
            if ($category) {
                if (empty ($list[$category])) {
                    $profile = $this->profile($category);
                    if ($profile['show']) {
                        $list[$category] = $profile;
                    }
                }
            } else {
                $list[$sn] = $this->profile($sn);
            }
        }
        return array_values($list);
    }

    public function check($uid, $lesson, $mode)
    {
        $res = [
            'lesson' => 1,
            'enroll' => [], // 可报名课程
            'access' => [], // 可观看课程
            'refund' => [], // 可退款课程
            'events' => [], // 当前课程状态
        ];
        if ($lesson[0] == 'L') { // 单课
            $recent = servLesson::sole($this->platform)->recent($lesson, $uid, [
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
        }
        if ($lesson[0] == 'S') { // 系列课
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $res = servLessonSeries::sole($this->platform)->checkLessons($lesson, $usn);
        }

        // 计算听课比例
        switch ($mode) {
            case 'access':
                return count($res['access']) / $res['lesson'];
            case 'confirm':
                return (count($res['access']) - count($res['refund'])) / $res['lesson'];
            default:
                if ($res['lesson'] == count($res['access']) && count($res['refund']) == 0) {
                    return 1;
                } else {
                    return 0;
                }
        }
    }
}