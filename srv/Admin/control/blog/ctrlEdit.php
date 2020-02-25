<?php


namespace Admin\blog;


use _\config;
use Admin\servBlog;

class ctrlEdit extends ctrl_
{
    public function _DO_()
    {
        $id = \input::get('id')->toInt();
        $this->view($id);
    }

    public function _POST_()
    {
        $id = \input::get('id', 0)->toInt();
        $data = \input::post(null)->value();
        if ($id) {
            servBlog::sole($this->platform)->modify($id, $data);
        } else {
            $id = servBlog::sole($this->platform)->create($data);
        }
        $this->view($id);
    }

    public function _POST_material($opt)
    {
        $id = \input::get('id', 0)->toInt();
        switch ($opt) {
            case 'prepare':
            case 'add':
            case 'delete':
        }
    }

    public function view($id)
    {
        $data = servBlog::sole($this->platform)->view($id);
        \view::tpl('page', [
            'page' => 'blog/edit',
            'data' => $data,
            'category' => servBlog::sole($this->platform)->category()
        ]);
    }


}