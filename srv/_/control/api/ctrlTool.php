<?php


namespace _\api;


use _\servContentSecurity;

class ctrlTool extends ctrl_
{
    public function _POST_textSecurity()
    {
        $content = $this->apiPOST('content');
        $result = servContentSecurity::sole($this->platform)->checkArticle($content);
        $this->apiSuccess(array_values($result));
    }

    public function _POST_imageSecurity()
    {
        $url = $this->apiPOST('url');
        $res = servContentSecurity::sole($this->platform)->checkImage($url);
        $this->apiSuccess($res);
    }

}