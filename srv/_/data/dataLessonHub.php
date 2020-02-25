<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class dataLessonHub extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_hub';

    const TAG_BLOCK = 'BLOCK';
    const TAG_TEACHER = 'TEACHER';

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function sliceByTag($tag, $cursor, int $limit)
    {
        $where = [
            'tag like ?' => [$tag ? "% $tag %" : '%'],
            'weight>0',
        ];
        $anchor = self::parseCursor($cursor);
        if ($anchor['id'] || $anchor['weight']) {
            $where['weight<? or (weight=? and id<?)'] = [$anchor['weight'], $anchor['weight'], $anchor['id']];
        }
        $_ = "order by weight desc, id desc";
        if ($limit) {
            $_ .= " limit $limit";
        }
        return $this->fetchAll($where, '*', null, null, $_);
    }

    public function search(array $querys, array $tags, $cursor, int $limit)
    {
        $where = [
            'weight>0',
        ];
        $state = 'title like ? or tag like ?';
        foreach ($querys as $query) {
            $where[$state] = ["%$query%", "% $query %"];
            $state .= ' '; // 避免where的key重复
        }
        $state = 'tag like ?';
        foreach ($tags as $tag) {
            $where[$state] = ["% $tag %"];
            $state .= ' ';
        }
        $anchor = self::parseCursor($cursor);
        if ($anchor['id'] || $anchor['weight']) {
            $where['weight<? or (weight=? and id<?)'] = [$anchor['weight'], $anchor['weight'], $anchor['id']];
        }
        $_ = "order by weight desc, id desc";
        if ($limit) {
            $_ .= " limit $limit";
        }
        return $this->fetchAll($where, '*', null, null, $_);
    }

    public function latestByTag($tag, $cursor, int $limit)
    {
        $where = [
            'tag like ?' => [$tag ? "% $tag %" : '%'],
            'weight>0',
        ];
        $anchor = self::parseCursor($cursor);
        if ($anchor['id']) {
            $where['id < ?'] = [$anchor['id']];
        }
        $_ = "order by id desc";
        if ($limit) {
            $_ .= " limit $limit";
        }
        return $this->fetchAll($where, '*', null, null, $_);
    }

    public static function parseCursor($cursor)
    {
        $flags = explode('-', $cursor);
        return [
            'id' => $flags[0] ?? 0,
            'weight' => $flags[1] ?? 0,
            'tms' => Tool::timeDecode($flags[2] ?? 0)
        ];
    }

    public static function buildCursor($source)
    {
        $cursor = ($source['id'] ?? 0) . '-' . ($source['weight'] ?? 0) . '-' . Tool::timeEncode($source['tms'] ?? 0);
        return $cursor;
    }


}