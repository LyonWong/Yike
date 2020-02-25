<?php


namespace _;

require_once PATH_ROOT . '/library/tools/Parsedown.php';

use Core\unitDoAction;

class ctrlBlog extends ctrl_
{
    use unitDoAction;

    public $platform;

    public function _DO_()
    {
        $this->_DO_list();
    }

    public function _DO_list()
    {
        $category = \input::get('category', '_')->value();
        $cursor = \input::get('cursor', '--')->value();
        $limit = \input::get('limit', 10)->toInt();
        $list = servBlog::sole($this->platform)->slice($category, $cursor, $limit);
        $foot = end($list);
        if ($limit == count($list)) {
            $turn = "./blog-list?category=$category&cursor=$foot[cursor]";
        } else {
            $turn = "./blog-list?category=$category";
        }
        /* 上下翻页
        $head = reset($list);
        if ($limit > 0) {
            if ($limit > count($list)) {
                $next = '';
            } else {
                $next = "./blog-list?category=$category&cursor=$foot[cursor]";
            }
            if ($cursor == '--') {
                $prev = '';
            } else {
                $prev = "./blog-list?category=$category&cursor=$head[cursor]&limit=-$limit";
            }
        } else {
            $next = "./blog-list?category=$category&cursor=$foot[cursor]";
            if ($limit + count($list) == 0) {
                $prev = "./blog-list?category=$category&cursor=$head[cursor]&limit=$limit";
            } else {
                $prev = '';
            }
        }*/
        \view::tpl('blog/list', [
            'list' => $list,
            'turn' => $turn,
//            'prev' => $prev,
//            'next' => $next,
        ]);
    }

    public function _DO_view($sn)
    {
        $data = servBlog::sole($this->platform)->view($sn);
        $parseDown = new \Parsedown();
        $data['markdown'] = $parseDown->text($data['content']);
        \view::tpl('blog/view', $data);
    }

}