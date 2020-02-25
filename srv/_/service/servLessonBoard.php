<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class servLessonBoard extends serv_
{
    use unitInstance;

    const SORT_BY_TIME_ASC = 'time_asc';
    const SORT_BY_TIME_DESC = 'time_desc';
    const SORT_BY_WEIGHT = 'weight';

    const TYPE_MAP = [
        dataLessonBoard::TYPE_ARGUE => 'argue',
        dataLessonBoard::TYPE_NULL => 'null',
    ];

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function brief($cursor)
    {
        $anchor = $this->parseCursor($cursor);
        $res = dataLessonBoard::sole($this->platform)->inquireOne(['id' => $anchor['id']],
            ['id', '_id', 'uid', 'lesson_id', 'i_type', 'stats']);
        return $res;
    }

    /**
     * 分段拉取
     * @param $type
     * @param $lessonSn
     * @param $sort
     * @param $cursor
     * @param $limit
     * @param array $filter
     * @return array|bool
     */
    public function slice($type, $lessonSn, $sort, $cursor, $limit, $filter = [])
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $anchor = $this->parseCursor($cursor);
        $fields = '*';
        $filter = array_merge($filter, [
            'id<>?' => [$anchor['id']],
            'weight >= 0',
        ]);
        $iType = array_search($type, self::TYPE_MAP);
        if (!$iType) {
            $filter['(i_type=? and weight>0) or i_type>?'] = [dataLessonBoard::TYPE_LIKE, dataLessonBoard::TYPE_LIKE];
        }
        switch ($sort) {
            case self::SORT_BY_TIME_DESC:
                if ($anchor['tms']) {
                    $filter['tms_create<=?'] = [$anchor['tms']];
                }
                $orderBy = "tms_create desc,id desc";
                break;
            case self::SORT_BY_TIME_ASC:
                if ($anchor['tms']) {
                    $filter['tms_create>=?'] = [$anchor['tms']];
                }
                $orderBy = "tms_create,id asc";
                break;
            case self::SORT_BY_WEIGHT:
                if ($anchor['id'] || $anchor['weight']) {
                    $filter['weight<? or (weight=? and id<?)'] = [$anchor['weight'], $anchor['weight'], $anchor['id']];
                }
                $orderBy = "weight desc,id desc";
                break;
            default:
                return false;
        }
        $res = dataLessonBoard::sole($this->platform)->slice($iType, $lessonId, $fields, $filter, $orderBy, intval($limit));
        foreach ($res as &$row) {
            $row = $this->boost($row);
        }
        return $res;
    }

    public function focus($target)
    {
        $daoBoard = dataLessonBoard::sole($this->platform);
        $anchor = $this->parseCursor($target);
        $the = $daoBoard->fetchOne(['id' => $anchor['id']], '*');
        return $this->boost($the);
    }

    /**
     * 获取回复$target的列表
     * @param $target
     * @param $cursor
     * @param $limit
     * @return array
     */
    public function refer($target, $cursor, $limit)
    {
        $daoBoard = dataLessonBoard::sole($this->platform);
        $anchor = $this->parseCursor($target);
        $_anchor = $this->parseCursor($cursor);
        $the = $daoBoard->fetchOne(['id' => $anchor['id']], '*');
        $filter = ['_id' => $the['id'], 'id>?' => [$_anchor['id']], 'weight>=0'];
        $res = $daoBoard->slice($the['i_type'], $the['lesson_id'], '*', $filter, "id", intval($limit));
        foreach ($res as &$row) {
            $row = $this->boost($row, false);
        }
        return $res;
    }

    /**
     * 以$target所指的记录为核心展开
     * @param $target
     * @param $cursor
     * @param $limit
     * @return array
     */
    public function assoc($target, $cursor, $limit)
    {
        $daoBoard = dataLessonBoard::sole($this->platform);
        $anchor = $this->parseCursor($target);
        $_anchor = $this->parseCursor($cursor);
        $the = $daoBoard->fetchOne(['id' => $anchor['id']], '*');
        $filter = [
            'id_' => $the['id_'] ?: $the['id'],
            'id>?' => [$_anchor['id']],
            'id<>?' => [$anchor['id']],
            'weight>=0'
        ];
        $res = $daoBoard->slice($the['i_type'], $the['lesson_id'], '*', $filter, "id", intval($limit));
        foreach ($res as &$row) {
            $row = $this->boost($row, true);
        }
        return $res;
    }

    /**
     * $target所在的对话链
     * @param $target
     * @param $cursor
     * @param $limit
     * @return array
     */
    public function chain($target, $cursor, $limit)
    {
        $daoBoard = dataLessonBoard::sole($this->platform);
        $anchor = $this->parseCursor($target);
        $_anchor = $this->parseCursor($cursor);
        $the = $daoBoard->inquireOne(['id' => $anchor['id']], '*');
        $filter = [
            'id_' => $the['id_'] ?: $the['id'],
            'extra->"$.chain"=?' => [$the['extra']['chain'] ?? '|-'],
            'id>?' => [$_anchor['id'] ?? 0],
            'weight>=0'];
        $res = $daoBoard->slice($the['i_type'], $the['lesson_id'], '*', $filter, "id", intval($limit));
        $withRefer = $_anchor['id'] ? false : true;
        foreach ($res as &$row) {
            $row = $this->boost($row, true);
            $withRefer = false;
        }
        return $res;
    }

    public function boost($row, $withRefer = true)
    {
        $dao = dataLessonBoard::sole($this->platform);
        $row = $dao->_inquireParse($row);
        if ($row['_id'] && $withRefer) {
            $_row = $dao->fetchOne(['id' => $row['_id']], '*');
            $_ = $dao->_inquireParse($_row);
            if ($row['i_type'] == dataLessonBoard::TYPE_LIKE) {
                return $this->boost($_row, $withRefer);
            }

            switch ($_['i_type']) {
                case dataLessonBoard::TYPE_DELETE:
                case dataLessonBoard::TYPE_TIPOFF:
                case dataLessonBoard::TYPE_HIDDEN:
                    $row['refer'] = [
                        'user' => servUser::sole($this->platform)->uid2profile($_['uid']),
                        'message' => [
                            'text' => '[已删除]'
                        ],
                        'cursor' => $this->id2cursor($_['id']),
                        'deleted' => true
                    ];
                    break;
                default:
                    if (mb_strlen($_['message']['text'] ?? '') > 101) {
                        $_['message']['text'] = mb_substr($_['message']['text'], 0, 100) . '...';
                    }
                    $row['refer'] = [
                        'user' => servUser::sole($this->platform)->uid2profile($_['uid']),
                        'message' => $this->buildMessage($_['message']),
                        'tms_create' => $_['tms_create'],
                        'cursor' => $this->id2cursor($_['id']),
                    ];
                    break;
            }

        }
        $row['cursor'] = $this->makeCursor($row['id'], $row['tms_create'], $row['weight']);
        $row['user'] = servUser::sole($this->platform)->uid2profile($row['uid']);
        if ($row['message'] && $row['i_type'] > 0) {
            $this->buildMessage($row['message']);
        } else {
            $row['message'] = [
                'text' => '[已删除]'
            ];
        }
        $stats = $row['stats'] ?: [];
        $stats['liked'] = $stats['liked'] ?? 0;
        $stats['reply'] = $stats['reply'] ?? 0;
        return [
            'type' => self::TYPE_MAP[$row['i_type']] ?? null,
            'user' => $row['user'],
            'refer' => $row['refer'] ?? null,
            'message' => $row['message'],
            'stats' => $stats,
            'tms_create' => $row['tms_create'],
            'cursor' => $row['cursor'],
        ];
    }

    protected function buildMessage(&$message)
    {
        foreach ($message as $key => &$item) {
            switch ($key) {
                case 'image':
                    foreach ($item as &$raw) {
                        $raw = \view::upload($raw);
                    }

                    break;
                case 'audio':
                case 'file':
                    $item = \view::upload($item);
                    break;
            }
        }
        return $message;
    }

    public function coself($lessonId, $uid)
    {
        $res = dataLessonBoard::sole($this->platform)->fetchAll(['lesson_id' => $lessonId, 'uid' => $uid, 'i_type<0'],
            ['_id', 'i_type', 'weight', 'tms_create']);
        $cos = [];
        foreach ($res as $row) {
            switch ($row['i_type']) {
                case dataLessonBoard::TYPE_LIKE:
                    if ($row['weight'] > 0) {
                        $cos[$row['_id']]['isLike'] = true;
                    }
                    break;
            }
        }
        return $cos;
    }


    /**
     * 评论
     * @param $type
     * @param $lessonId
     * @param $uid
     * @param unitBoardMessage $message
     * @return bool|string
     */
    public function comment($type, $lessonId, $uid, unitBoardMessage $message)
    {
        $iType = array_search($type, self::TYPE_MAP);
        if ($iType === false) {
            return false;
        }
        $id = dataLessonBoard::sole($this->platform)->append($iType, $lessonId, $uid, $message);
        return $this->id2cursor($id);
    }

    /**
     * 回复
     * @param $cursor
     * @param $uid
     * @param unitBoardMessage $message
     * @return string
     * @internal param $boardId
     */
    public function reply($cursor, $uid, unitBoardMessage $message)
    {
        $anchor = $this->parseCursor($cursor);
        $dao = dataLessonBoard::sole($this->platform);
        $id = $dao->reply($anchor['id'], $uid, $message);
        $pre = $dao->fetchOne(['id' => $anchor['id']], ['uid','id_','lesson_id']); // 被回复的帖子
        $dao->update("stats=json_set(stats, 
         '$.reply', if(stats->'$.reply', stats->'$.reply', 0)+1,
         '$.assoc', if(stats->'$.assoc', stats->'$.assoc', 0)+1),
         weight=weight+1", ['id' => $anchor['id']]);
        if ($pre['id_']) {
            $dao->update("stats=json_set(stats, 
             '$.assoc', if(stats->'$.assoc', stats->'$.assoc', 0)+1),
             weight=weight+1", ['id' => $pre['id_']]);
        }
        // 发送回复通知
//        var_dump ($pre);
        servNotify::sole($this->platform)->boardReplyNotify($pre['uid'], $uid, $pre['lesson_id'], $cursor);
        return $this->id2cursor($id);
    }

    public function replyMsg2user($tuid, $cursor, $lessonId, $text)
    {
        $anchor = $this->parseCursor($cursor);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $tuid], 'name', 'name');
        $lessonDetail = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], ['sn', 'title']);
        $uid = dataLessonBoard::sole($this->platform)->fetchOne(['id' => $anchor['id']], 'uid', 'uid');
        if ($tuid != $uid) {
            servMpMsg::sole($this->platform)->teacherReplyNotice($teacherName, $uid, $lessonDetail['title'], $lessonDetail['sn'], $text, $cursor);

        }

    }

    /**
     * 点赞/取消
     * @param $cursor
     * @param $uid
     * @return array
     * @internal param $boardId
     */
    public function like($cursor, $uid)
    {
        $dao = dataLessonBoard::sole($this->platform);
        $anchor = $this->parseCursor($cursor);

        $res = $dao->inquireOne(['id' => $anchor['id']], ['lesson_id', 'stats']);
        $rec = ['_id' => $anchor['id'], 'lesson_id' => $res['lesson_id'], 'i_type' => dataLessonBoard::TYPE_LIKE, 'uid' => $uid];
        $pre = $dao->fetchOne(
            $rec,
            ['id', 'weight']
        );

        if ($pre) {
            $weight = $pre['weight'] ? 0 : 1;
            $dao->update(['weight' => $weight], ['id' => $pre['id']]);
        } else {
            $weight = 1;
            $rec['weight'] = 1;
            $dao->insert($rec);
        }

        $stats = $res['stats'];
        $stats['liked'] = ($stats['liked'] ?? 0) + ($weight ? 1 : -1);

        unset($rec['uid']);
//        $stats['liked'] = $dao->fetchOne($rec, "sum(weight)", 0);

        $dao->update([
            'stats' => json_encode($stats),
            'weight' => $stats['liked'] + ($stats['reply'] ?? 0),
        ], ['id' => $anchor['id']]);

        return [
            'isLike' => boolval($weight),
            'liked' => $stats['liked'],
        ];
    }

    /**
     * 删除
     * @param $cursor
     * @param $uid
     * @param $reason
     * @return bool|mixed
     */
    public function deleteByCursor($cursor, $uid, $reason)
    {
        $anchor = $this->parseCursor($cursor);
        return self::delete($anchor['id'], $uid, $reason)->rowCount();

    }

    public function delete($boardId, $uid, $reason)
    {
        $dao = dataLessonBoard::sole($this->platform);
        $_id_ = $dao->fetchOne(['id' => $boardId], ['_id', 'id_']);

        $rec['message'] = json_encode(['reason' => $reason, '_uid' => $uid]);
        $rec['i_type'] = dataLessonBoard::TYPE_DELETE;

        // 删除相关回复统计及权重
        if ($_id_['_id']) { // 扣除直接回复的留言权重
            $dao->update("stats=json_set(stats, 
            '$.reply', stats->'$.reply'-1,
            '$.assoc', stats->'$.assoc'-1
            ), weight=weight-1", ['id' => $_id_['_id']]);
        }
        if ($_id_['id_'] && $_id_['id_'] != $_id_['_id']) { // 扣除起源留言的权重
            $dao->update("stats=json_set(stats, 
            '$.assoc', stats->'$.assoc'-1
            ), weight=weight-1", ['id' => $_id_['id_']]);
        }
        return $dao->update($rec, ['id' => $boardId]);

    }

    public function hide($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        $ids = dataLessonBoard::sole($this->platform)->fetchAll(['uid' => $uid, 'lesson_id' => $lessonId, 'i_type>=0'], 'id');
        foreach ($ids as $item) {
            dataLessonBoard::sole($this->platform)->update(['i_type' => dataLessonBoard::TYPE_HIDDEN], ['id' => $item['id']]);
        }
    }

    /**
     * 举报
     * @param $target
     * @param $uid
     * @param unitBoardMessage $message
     * @return bool|\clsPDOStatement|mixed
     */
    public function tipoff($target, $uid, unitBoardMessage $message)
    {
        $anchor = $this->parseCursor($target);
        $dao = dataLessonBoard::sole($this->platform);
        return (bool)$dao->tipoff($anchor['id'], $uid, $message);
    }

    public function id2cursor($id)
    {
        $res = dataLessonBoard::sole($this->platform)->fetchOne(['id' => $id], ['tms_create', 'weight']);
        return $res ? $this->makeCursor($id, $res['tms_create'], $res['weight']) : false;
    }

    public function makeCursor($id, $tms, $weight)
    {
        $tms = Tool::timeEncode($tms);
        $cursor = "$id-$tms-$weight";
        return $cursor;
    }

    public function parseCursor($cursor)
    {
        list($res['id'], $res['tms'], $res['weight']) = explode('-', $cursor ?: '--');
        $res['tms'] = $res['tms'] ? Tool::timeDecode($res['tms']) : null;
        return $res;
    }


    public function boardListByTime($tmsStart, $tmsEnd)
    {
        $list = dataLessonBoard::mysql()->run("select id,uid,lesson_id from lesson_board where id in(SELECT _id from lesson_board where (tms_create between '$tmsStart' and '$tmsEnd') and i_type>0 AND _id>0 ) ORDER BY id desc")->fetchAll();
        $data = [];
        foreach ($list as $item) {
            $data[$item['uid']][] = $item;
        }
        return $data;

    }


    public function boardLessonListByTime($tmsStart, $tmsEnd)
    {
        $list = dataLessonBoard::mysql()->run("SELECT  id,lesson_id,uid from lesson_board where (tms_create between '$tmsStart' and '$tmsEnd') and i_type>0  ORDER by id DESC ")->fetchAll();
        $data = [];
        foreach ($list as $item) {
            $data[$item['lesson_id']][] = $item;
        }

        return $data;

    }


}