<?php


namespace _\cli;


use _\config;
use Core\library\Tool;

class ctrl extends ctrl_
{
    public function _DO_secret($length)
    {
        echo Tool::genSecret($length);
    }

    public function _DO_nonce($length)
    {
        echo Tool::genSecret($length, Tool::STR_FORMAL).LF;
    }

    public function _DO_static()
    {
        $pre = PATH_ROOT.'/_/view/assets/';
        $map = [
            'live' => PATH_ROOT.'/_/view/template/live.php',
            'student' => PATH_ROOT.'/Student/view/template/idx.php',
            'teacher' => PATH_ROOT.'/Teacher/view/template/idx.php',
        ];
        foreach ($map as $src => $target) {
            $file = "$pre/$src.html";
            $res = file_get_contents($file);
            $idx =  preg_replace('#"(http|https)://assets.yike.local/(.*?)"#', '"<?=view::src("$2", "_")?>"', $res);
            file_put_contents($target, $idx);
        }

        $AppId = config::load('tencent', 'mta', 'AppId');
        $_js = file_get_contents(PATH_ROOT.'/_/view/assets/-.js');
        $js = preg_replace('#APP_ID#', $AppId, $_js);
        file_put_contents(PATH_ROOT.'/_/view/assets/_.js', $js);
    }

}