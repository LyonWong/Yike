<?php


namespace Admin\blog;


use _\config;
use Admin\servBlog;

class ctrlList extends ctrl_
{
    public function _DO_()
    {
        $srv = servBlog::sole($this->platform);
        $predom = config::load('boot', 'public', 'domain');
        $preview = "$_SERVER[REQUEST_SCHEME]://$predom";
        \view::tpl('page', [
            'page' => 'blog/list',
            'category' => $srv->category(),
            'list' => $srv->list(),
            'preview' => $preview
        ]);
    }
}