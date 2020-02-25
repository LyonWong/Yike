<?php
/**
 * mysql未指定精度的float型，在涉及等值判断(=, in, ..)时会失效，用like代替
 */

namespace _;


use Core\unitInstance;

class dataLessonPrepare extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_prepare';

    const TYPE_TEXT = 1;

    const TYPE_IMAGE = 2;

    const TYPE_AUDIO = 3;

    const TYPE_VIDEO = 4;

    const TYPE_MARK = 5;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function count($lessonId)
    {
        return $this->fetchOne(['lesson_id' => $lessonId], 'count(*)', 0);
    }

    public function preSeqno($lessonId, $seqno)
    {
        return $this->mysql->select($this->TABLE, ['seqno'],
            ['lesson_id' => $lessonId, "seqno < ?" => [$seqno], 'seqno not like ?'=>[$seqno] ],
            "order by seqno desc limit 1")->fetch(0);
    }

    public function genSeqno($lessonId)
    {
        $preno = $this->fetchOne(['lesson_id' => $lessonId], "max(seqno)", 0);
        return floor($preno)+1;
    }

    public function append($lessonId, $iType, $content)
    {
        $seqno = $this->genSeqno($lessonId);
        $this->insert([
            'lesson_id' => $lessonId,
            'i_type' => $iType,
            'content' => json_encode($content),
            'seqno' => $seqno
        ]);
        return $seqno;
    }

    public function slice($lessonId, $cursor, $toward, int $limit, array $filter = [])
    {
        $where = array_merge([
            'lesson_id' => $lessonId,
        ], $filter);
        switch ($toward) {
            case self::TOWARD_NEXT:
                $where['seqno > ?'] = [$cursor];
                break;
            case self::TOWARD_PREV:
                $where['seqno < ?'] = [$cursor];
                break;
            case self::TOWARD_FORE:
                $where['seqno >= ?'] = [$cursor];
                break;
            case self::TOWARD_HIND:
                $where['seqno <= ?'] = [$cursor];
                break;
            case self::TOWARD_SELF:
                $where['seqno like ?'] = [$cursor];
                break;
        }
        $_ = "order by seqno";
        if ($limit > 0) {
            $_ .= " limit $limit";
        }
        return $this->mysql->select($this->TABLE,
            ['i_type', 'content', 'seqno', 'tms'],
            $where,
            $_
        )->fetchAll();
    }

}