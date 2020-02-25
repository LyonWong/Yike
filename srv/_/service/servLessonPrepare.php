<?php


namespace _;


use Core\library\Math;
use Core\unitFile;
use Core\unitInstance;

class servLessonPrepare extends serv_
{
    use unitInstance;
    use unitFile;

    const TYPE_MAP = [
        dataLessonPrepare::TYPE_TEXT => 'text',
        dataLessonPrepare::TYPE_IMAGE => 'image',
        dataLessonPrepare::TYPE_AUDIO => 'audio',
        dataLessonPrepare::TYPE_VIDEO => 'video',
        dataLessonPrepare::TYPE_MARK => 'mark',
    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function slice($lessonSn, $cursor, $toward, $limit)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $rows = dataLessonPrepare::sole($this->platform)->slice($lessonId, $cursor, $toward, intval($limit));
        foreach ($rows as &$row) {
            $row = $this->boost($row);
        }
        return $rows;
    }

    public function slicePreview($lessonSn, $cursor, $toward, $limit)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $rows = dataLessonPrepare::sole($this->platform)->slice($lessonId, $cursor, $toward, intval($limit));
        foreach ($rows as &$row) {
            $row = $this->boostPreview($row);
        }
        return $rows;
    }

    public function fetch($lessonSn, $cursor)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $row = dataLessonPrepare::sole($this->platform)->fetchOne(
            ['lesson_id' => $lessonId, 'seqno like ?' => [$cursor]],
            '*'
        );
        return $this->boost($row);
    }

    public function create($lessonSn, $type, $content)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $dict = array_flip(self::TYPE_MAP);
        // 别名
        $dict['markdown'] = $dict['text'];
        $dict['bookmark'] = $dict['mark'];
        if ($iType = $dict[$type] ?? null) {
            return dataLessonPrepare::sole($this->platform)->append($lessonId, $iType, $content);
        } else {
            return false;
        }
    }

    public function send($tusn, $lessonSn, $cursor)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $row = dataLessonPrepare::sole($this->platform)->fetchOne(
            ['lesson_id' => $lessonId, 'seqno like ?' => [$cursor]],
            '*'
        );
        $srvTim = servTIM::sole($this->platform);
        $room = servLesson::sole($this->platform)->sn2room($lessonSn);
        $item = $this->boost($row);
        switch ($row['i_type']) {
            case dataLessonPrepare::TYPE_TEXT:
                $res = $srvTim->tim()->group_send_group_msg($tusn, $room['teach'], $item['content']);
                break;
            case dataLessonPrepare::TYPE_IMAGE;
                $msgContentElem = [
                    'MsgType' => 'TIMCustomElem',
                    'MsgContent' => [
                        'Data' => $item['content'] . '?imageView2/0/w/256#' . $item['content'],
                        'Desc' => 'IMAGE',
                        'Ext' => pathinfo($item['content'])['filename'] ?? ''
                    ]
                ];
                $res = $srvTim->tim()->group_send_group_msg2($tusn, $room['teach'], [$msgContentElem]);
                break;
            case dataLessonPrepare::TYPE_AUDIO:
                $msgContentElem = [
                    'MsgType' => 'TIMCustomElem',
                    'MsgContent' => [
                        'Data' => $item['content'],
                        'Desc' => 'SOUND',
                        'Ext' => pathinfo($item['content'])['filename'] ?? ''
                    ]
                ];
                $res = $srvTim->tim()->group_send_group_msg2($tusn, $room['teach'], [$msgContentElem]);
                break;
            case dataLessonPrepare::TYPE_VIDEO:
                $msgContentElem = [
                    'MsgType' => 'TIMCustomElem',
                    'MsgContent' => [
                        'Data' => $item['content'].'.m3u8',
                        'Desc' => 'VIDEO',
                        'Ext' => pathinfo($item['content'])['filename'] ?? ''
                    ]
                ];
                $res = $srvTim->tim()->group_send_group_msg2($tusn, $room['teach'], [$msgContentElem]);
                break;
            case dataLessonPrepare::TYPE_MARK:
                $msgContentElem = [
                    'MsgType' => 'TIMCustomElem',
                    'MsgContent' => [
                        'Data' => $item['content'],
                        'Desc' => 'MARK',
                        'Ext' => 'mark'
                    ]
                ];
                $res = $srvTim->tim()->group_send_group_msg2($tusn, $room['teach'], [$msgContentElem]);
                break;
            default:
                $res = [];
                break;
        }
        return $res && $res['ErrorCode'] == 0;
    }

    public function delete($lessonSn, $cursor)
{
    $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
    return dataLessonPrepare::sole($this->platform)->delete([
        'lesson_id' => $lessonId,
        'seqno like ?' => [$cursor]
    ])->rowCount();
}

    public function modify($lessonSn, $cursor, $type, $content)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $dict = array_flip(self::TYPE_MAP);
        // 别名
        $dict['markdown'] = $dict['text'];
        $dict['bookmark'] = $dict['mark'];
        if (!($iType = $dict[$type] ?? null)) {
            return false; // 类型错误
        }
        $where = [
            'lesson_id' =>$lessonId,
            'seqno like ?' => [$cursor]
        ];
//        print_r($where);
        $prev = dataLessonPrepare::sole($this->platform)->fetchOne($where, 'content', 0);
//        var_dump($prev);
        $content = array_merge(json_decode($prev, true), $content);
        return dataLessonPrepare::sole($this->platform)->update([
            'i_type' => $iType,
            'content'=>json_encode($content)
        ],[
            'lesson_id' => $lessonId,
            'seqno like ?' => [$cursor]
        ])->rowCount();
    }

    public function release($lessonSn)
    {
        $rows = $this->slicePreview($lessonSn, '-', data::TOWARD_NEXT, -1);
        $srvLesson = servLesson::sole($this->platform);
        $profile = $srvLesson->profile($lessonSn);
        if ($profile['step'] == servLesson::STEP_MAP[dataLesson::STEP_FINISH]) {
            return false;
        }
        $fromUsn = $profile['teacher']['sn'];
        dataLessonRecord::sole($this->platform)->update(['i_type' => -1], ['lesson_id' => $profile['id']]);
        foreach ($rows as $row) {
            $srvLesson->createRecord($lessonSn, $fromUsn, servLesson::RECORD_FORM_MAP[dataLesson::FORM_VIEW], json_encode($row['content']));
        }
        servTrigger::sole($this->platform)->onLessonOpen($lessonSn);
        servTrigger::sole($this->platform)->onLessonFinish($lessonSn);
        $lessonId = $srvLesson->sn2id($lessonSn);
        dataLesson::sole($this->platform)->update([
            'i_step' => dataLesson::STEP_FINISH
        ], ['id' => $lessonId]);
        if ($profile['form'] == servLesson::FORM_MAP[dataLesson::FORM_ARTICLE]) { // 写入发布时间
            $date = date('Y-m-d H:i');
            dataLesson::sole($this->platform)->update("plan=json_set(plan, '$.dtm_start', '$date')", ['id' => $lessonId]);
            if (($profile['categoryInfo']['form']??null) == servLesson::FORM_MAP[dataLesson::FORM_COLUMN]
                && ($profile['categoryInfo']['conf']['inform']??true)
                && $profile['isPublic']) { // 推送专栏更新通知
                servMpMsg::sole($this->platform)->sendSubscribeUpdate($lessonSn);
            }
        }
        return true;
    }

    public function swap($lessonSn, $cursorA, $cursorB)
    {
        if ($cursorA == $cursorB) {
            return false;
        }
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $daoPrepare = dataLessonPrepare::sole($this->platform);
        $ids = $daoPrepare->fetchAll(
            ['lesson_id' => $lessonId, "(seqno like ? or seqno like ?)" => [$cursorA, $cursorB]],
            'id',
            null,
            0
        );
        $figure = max(Math::decimalFigure($cursorA), Math::decimalFigure($cursorB));
        $daoPrepare->update(
            "seqno = 0 - seqno",
            ['id in (?,?)' => $ids]
        );
        $daoPrepare->update(
            "seqno=if(format(seqno,$figure)=-format($cursorA,$figure), $cursorB, $cursorA)",
            ['id in (?,?)' => $ids]
        );
        return true;
        /*
         * todo use transaction
        $daoPrepare->update(
            ["seqno"=>0],
            ['lesson_id' => $lessonId, 'seqno' => $cursorA]
        );
        $daoPrepare->update(
            ["seqno"=>$cursorA],
            ['lesson_id' => $lessonId, 'seqno' => $cursorB]
        );
        $daoPrepare->update(
            ["seqno" => $cursorB],
            ['lesson_id' => $lessonId, 'seqno' => 0]
        );
        */
    }

    public function jump($lessonSn, $cursor, $before)
    {
        if ($cursor == $before) {
            return false;
        }
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $daoPrepare = dataLessonPrepare::sole($this->platform);
        $previous = $daoPrepare->preSeqno($lessonId, $before);
        $figure = max(Math::decimalFigure($before), Math::decimalFigure($previous));
        $step = pow(10, -$figure);
        if (round($previous + $step, $figure) >= round($before, $figure)) {
            $step /= 10;
        }
        $seqno = $previous+$step;
        if ($daoPrepare->update(
            ['seqno' => $seqno],
            ['lesson_id' => $lessonId, 'seqno like ?' => [$cursor]]
        )->rowCount()
        ) {
            return $seqno;
        } else {
            return false;
        }
    }

    public function boost($row)
    {
        $content = json_decode($row['content'],true);
        switch ($row['i_type']) {
            case dataLessonPrepare::TYPE_AUDIO:
                $path = $content['path'] ?? '';
                $note = $content['note'] ?? '';
                $content = config::load('boot', 'public', 'upload') . "/$path";
                break;
            case dataLessonPrepare::TYPE_IMAGE:
            case dataLessonPrepare::TYPE_VIDEO:
                $src = $content['path'] ?? $content;
                $content = config::load('boot', 'public', 'upload') . "/$src";
                break;
        }
        $data = [
            'type' => self::TYPE_MAP[$row['i_type']],
            'content' => $content,
            'seqno' => $row['seqno'],
            'tms' => $row['tms'],
        ];
//        if($row['i_type'] == dataLessonPrepare::TYPE_AUDIO) {
//            $data['note'] = $note;
//        }
        if (isset($content['note'])) {
            $data['note'] = $content['note'];
        }
        return $data;
    }

    public function boostPreview($row)
    {
        $data = [
            'cursor' => $row['seqno'],
            'tms' => $row['tms'],
        ];
        $content = json_decode($row['content'], true);
        $resourceURL = config::load('boot', 'public', 'upload');
        switch ($row['i_type']) {
            case dataLessonPrepare::TYPE_TEXT:
                $data['content'] = [
                    'type' => 'markdown',
                    'text' => isset($content['note']) ? $content['note'] : $content
                ];
                break;
            case dataLessonPrepare::TYPE_IMAGE:
                $data['content'] = [
                    'type' => 'image',
                    'text' => $content['note'] ?? '',
                    'src' => isset($content['path']) ? "$resourceURL/$content[path]" : "$resourceURL/$content"
                ];
                break;
            case dataLessonPrepare::TYPE_AUDIO:
                $data['content'] = [
                    'type' => 'audio',
                    'text' => $content['note'] ?? '',
                    'src' => "$resourceURL/$content[path]"
                ];
                break;
            case dataLessonPrepare::TYPE_VIDEO:
                $data['content'] = [
                    'type' => 'video',
                    'text' => $content['note'] ?? '',
                    'src' => isset($content['path']) ? "$resourceURL/$content[path]" : "$resourceURL/$content"
                ];
                break;
            default:
                $data['content'] = [
                    'type' => self::TYPE_MAP[$row['i_type']],
                    'text' => $content['note'] ?? '',
                    'src' => isset($content['path']) ? "$resourceURL/$content[path]" : "$resourceURL/$content"
                ];
                break;
        }
        $data['content']['free'] = $content['free'] ?? false;
        $data['content'] = array_merge($data['content'], array_filter([
            'length' => $content['length'] ?? null,
            'duration' => $content['duration'] ?? null,
        ]));
        return $data;
    }


}