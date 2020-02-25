<?php


namespace _;


use Core\library\Tool;
use Core\unitInstance;

class dataBlog extends dataSole
{
    use unitInstance;

    const TABLE = 'blog';

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($data)
    {
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(self::SN_BLOG);
            self::mysql()->insert(self::TABLE, $data);
            $id = (int)self::mysql()->lastInsertId();
        } while (!$id && --$try>0);
        return $id;
    }

    public function sliceByCategory($category, $cursor, int $limit)
    {
        $where = [
            'category' => $category,
            'weight>0',
        ];
        $anchor = self::parseCursor($cursor);
        if ($limit > 0) {
            if ($anchor['id'] || $anchor['weight']) {
                $where['weight<? or (weight=? and id<?)'] = [$anchor['weight'], $anchor['weight'], $anchor['id']];
            }
            $_ = "order by weight desc , id desc limit $limit";
        } else {
            if ($anchor['id'] || $anchor['weight']) {
                $where['weight>? or (weight=? and id>?)'] = [$anchor['weight'], $anchor['weight'], $anchor['id']];
            }
            $_ = "order by weight, id limit " . (-$limit);
        }
        $res = $this->fetchAll($where, '*', null, null, $_);
        if ($limit < 0) {
            $res = array_reverse($res);
        }
        return $res;
    }

    public static function parseCursor($cursor)
    {
        $flags = explode('-', $cursor);
        return [
            'id' => $flags[0] ?? 0,
            'weight' => $flags[1] ?? 0,
            'tms_update' => Tool::timeDecode($flags[2] ?? 0)
        ];
    }

    public static function buildCursor($source)
    {
        $cursor = ($source['id'] ?? 0) . '-' . ($source['weight'] ?? 0) . '-' . Tool::timeEncode($source['tms_update'] ?? 0);
        return $cursor;
    }

    protected function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key) {
                case 'setting':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }

}