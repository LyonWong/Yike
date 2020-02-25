<?php


namespace Admin;


use _\dataBlog;
use Core\unitInstance_;

class servBlog extends \_\servBlog
{
    use unitInstance_;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($data)
    {
        $dao = dataBlog::sole($this->platform);
        $data['setting'] = json_encode($data['setting']??[], JSON_FORCE_OBJECT);
        $data['weight'] = $data['weight'] ?? 1;
        $id = $dao->append($data);
        return $id;
    }

    public function modify($id, $data)
    {
        $data['setting'] = isset($data['setting']) ? json_encode($data['setting'], JSON_FORCE_OBJECT) : null;
        $data = array_filter($data);
        return dataBlog::sole($this->platform)->update($data, ['id' => $id])->rowCount();
    }

    public function list()
    {
        $res = dataBlog::sole($this->platform)->fetchAll(null, ['id', 'sn', 'title', 'category', 'tags', 'weight', 'tms_create', 'tms_update'], null, null, 'order by id desc');
        return $res;
    }

    public function view($id)
    {
        $res = dataBlog::sole($this->platform)->inquireOne(['id' => $id], '*');
        return $res;
    }


}