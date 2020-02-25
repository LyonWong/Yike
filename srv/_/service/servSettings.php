<?php


namespace _;


use Core\unitInstance;

class servSettings extends serv_
{
    use unitInstance;

    const TYPE_MAP = [
        dataSettings::TYPE_MP_REPLY => 'mp_reply',
        dataSettings::TYPE_LESSON_TAG => 'lesson_tag'
    ];

    const TYPE_DICT = [
        'mp_reply' => '微信公众号自动回复',
        'lesson_tag' => '课程标签'
    ];

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function list($where=null)
    {
        $res = dataSettings::sole($this->platform)->fetchAll($where, '*');
        return $res;
    }

    public function match($iType, $pattern)
    {
        $row = dataSettings::sole($this->platform)->match($iType, $pattern);
        $row['datum'] = json_decode($row['datum'], true);
        return $row;
    }

    public function tags($iType)
    {
        $list = $this->list(['i_type' => $iType]);
        $tags = [];
        foreach ($list as $row) {
            $tags[$row['item']] = json_decode($row['datum'], true);
        }
        return $tags;
    }


}