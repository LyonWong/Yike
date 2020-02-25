<?php


namespace Admin;


use _\dataLessonHub;
use _\dataLessonSearch;
use Core\unitInstance_;

class servLessonHub extends \_\servLessonHub
{
    use unitInstance_;

    public static function sole($platfom)
    {
        return self::_singleton($platfom);
    }

    public function list()
    {
        $res = dataLessonHub::sole($this->platform)->fetchAll(null, '*', null ,null, 'order by id desc');
        return $res;
    }

    public function create($tsn, $tag, $weight)
    {
        if (!$target = $this->target($tsn) ) {
            return false;
        }
        $data = [
            'tsn' => $tsn,
            'title' => $target['title'],
            'tag' => $tag,
            'weight' => $weight,
        ];
        dataLessonHub::sole($this->platform)->insert($data, ['title', 'tag', 'weight']);
    }

    public function modify($tsn, $tag, $weight)
    {
        if (!$target = $this->target($tsn) ) {
            return false;
        }
        $data = [
            'title' => $target['title'],
            'tag' => $tag,
            'weight' => $weight,
        ];
        return (bool)dataLessonHub::sole($this->platform)->update($data, ['tsn' => $tsn])->rowCount();
    }
}
