<?php


namespace _\api;


use _\servBlog;

class ctrlBlog extends ctrl_
{
    public function _DO_list()
    {
        $category = $this->apiGET('category', '_');
        $cursor = $this->apiGET('cursor', '--');
        $limit = $this->apiGET('limit', 10);
        $list = servBlog::sole($this->platform)->slice($category, $cursor, $limit);
        $this->apiSuccess($list);
    }

    public function _DO_view()
    {
        $sn = $this->apiGET('sn');
        $detail = servBlog::sole($this->platform)->view($sn);
        $this->apiSuccess($detail);
    }

}