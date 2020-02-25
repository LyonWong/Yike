<?php


namespace _;


use Core\unitInstance;


class servRating extends serv_
{
    use unitInstance;

    const TOWARD_MAP = [
        'next' => dataRating::TOWAED_NEXT,
        'previous' => dataRating::TOWARD_PREVIOUS
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function rating($lessonSn, $cursor, $limit, $toward)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $ret = dataRating::sole($this->platform)->getList($cursor, $limit, $toward,
            ['lesson_id' => $lessonId, 'i_status >=' . dataRating::STATUS_NORMAL],
            'id as `cursor`,suid,score,remark,reply,tms,tms_reply', 'order by id desc');
        $ratings = [];
        if ($ret) {
            foreach ($ret as $rating) {
                $usn = servUser::sole($this->platform)->uid2usn($rating['suid']);
                $rating['user'] = servUser::sole($this->platform)->usn2profile($usn);
                $rating['remark'] = json_decode($rating['remark']);
                $rating['tms'] = date('Y-m-d H:i', strtotime($rating['tms']));
                unset($rating['suid']);
                $ratings[] = $rating;
            }
        }
        return $ratings;
    }

    public function ratingList($lessonSn, $limit, $page)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $rets = dataRating::sole($this->platform)->paging(
            $page,
            $limit,
            ['lesson_id' => $lessonId, 'i_status >=' . dataRating::STATUS_NORMAL],
            'id as `cursor`,suid,score,remark,reply,tms,tms_reply',
            'case when ifnull(remark,\'\')=\'\' then 0 else 1 end desc,id desc');
        $ratings = [];
        if ($rets['pages']) {
            foreach ($rets['pages'] as &$rating) {
                $usn = servUser::sole($this->platform)->uid2usn($rating['suid']);
                $rating['user'] = servUser::sole($this->platform)->usn2profile($usn);
                $rating['remark'] = json_decode($rating['remark']);
                $rating['tms'] = date('Y-m-d H:i', strtotime($rating['tms']));
                unset($rating['suid']);
                $ratings[] = $rating;
            }
        }
        return $rets;
    }

    public function rated($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        return dataRating::sole($this->platform)->fetchOne(['lesson_id' => $lessonId, 'suid' => $uid], ['score', 'remark', 'tms']) ? true : false;
    }

    public function checkRate($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $ret = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lessonId, $uid);
        //进入课堂且未评价过
        if ($ret['i_event'] >= dataLessonAccess::EVENT_ACCESS && self::rated($lessonSn, $uid) == false) {
            return true;
        }
        return false;
    }

    public function rate($score, $remark, $lessonSn, $uid)
    {
        $lessonInfo = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid']);
        if ($lessonInfo) {
            $originId = servLesson::sole($this->platform)->seekOriginId($lessonInfo['id'], $uid);
            stats\servLesson::sole($this->platform)->varRate($lessonInfo['id'], $originId, $score);
            return dataRating::sole($this->platform)->append($lessonInfo['id'], $lessonInfo['tuid'], $uid, $score, json_encode($remark, JSON_UNESCAPED_UNICODE));
        } else {
            return false;
        }
    }

    public function rateCount($lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        return dataRating::sole($this->platform)->getCount($lessonId);
    }

    public function stats($lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $stats = stats\servLesson::sole($this->platform)->getSummary($lessonId);
        return [
            'score' => $stats['lesson.rate.avg'] ?? 0,
            'turnout' => $stats['lesson.rate.count'] ?? 0
        ];
    }

    public function hide($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $ratedScore = dataRating::sole($this->platform)->fetchOne(['lesson_id'=>$lessonId, 'suid' => $uid], 'score', 0);
        if ($ratedScore) {
            $originId = servLesson::sole($this->platform)->seekOriginId($lessonId, $uid);
            stats\servLesson::sole($this->platform)->varRate($lessonId, $originId, $ratedScore, -1);
        }
        dataRating::sole($this->platform)->update(['i_status' => dataRating::STATUS_HIDDEN], ['lesson_id' => $lessonId, 'suid' => $uid]);
    }

    public function reply($id, $text, $tuid):bool
    {
        return (bool)dataRating::sole($this->platform)->update([
            'reply' => $text,
            'tms_reply' => date('Y-m-d H:i:s'),
        ], [
            'id' => $id,
            'tuid' => $tuid
        ])->rowCount();
    }


}