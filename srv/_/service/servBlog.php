<?php


namespace _;


use Core\unitInstance;

class servBlog extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function category()
    {
        return config::load('info', 'blog_category',null, []);
    }

    public function slice($category, $cursor, $limit)
    {
        $list = dataBlog::sole($this->platform)->sliceByCategory($category, $cursor, $limit);
        foreach ($list as &$item) {
            $item['cursor'] = dataBlog::buildCursor($item);
            $item['setting'] = json_decode($item['setting'], true);
            unset($item['id'], $item['weight']);
        }
        return $list;
    }

    public function view($sn)
    {
        $fields = [
            'title', 'category', 'tags', 'content', 'setting', 'tms_update'
        ];
        $res = dataBlog::sole($this->platform)->inquireOne(['sn' => $sn], $fields);
        return $res;
    }

}