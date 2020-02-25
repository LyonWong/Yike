<?php


namespace Admin;


use Core\library\Language;

class wdgtLang
{
    public static function switchDropdown()
    {
        $map = [
            'zh-cn' => [
                'name' => '简体中文',
                'flag' => 'cn',
            ],
            'en' => [
                'name' => 'English',
                'flag' => 'us',
            ],
            'ja' => [
                'name' => '日本語',
                'flag' => 'jp',
            ]
        ];
        $list = explode('|', \config::load('boot', 'public', 'language.support'));
        $lang = Language::ALIAS[servSession::$lang] ?? servSession::$lang;
        $src = \view::src("resource/metronic/img/flags/{$map[$lang]['flag']}.png");
        $html = "<a href='' class='dropdown-toggle' data-toggle='dropdown' data-hover='dropdown' data-close-others='true'>
                <img alt='lang-{$map[$lang]['name']}' src='$src'> 
                <span class='langname'>".servSession::$lang."</span>
                </a>";
        $html .= "<ul class='dropdown-menu dropdown-menu-default'>";
        foreach ($list as $lang) {
            $lang = Language::ALIAS[$lang] ?? $lang;
            if (empty($map[$lang])) {
                continue;
            }
            $src = \view::src("resource/metronic/img/flags/{$map[$lang]['flag']}.png");
            $html .= "<li><a href='javascript:Lang.switch(\"$lang\");'><img alt='lang-$lang' src='$src'/>{$map[$lang]['name']}</a></li>";
            unset($map[$lang]); // 避免重复
        }
        $html .= "</ul>";
        return $html;
    }

    public static function _file_($path)
    {
        return Language::file(servSession::$lang, $path);
    }

    public static function base($key)
    {
        return Language::sole(servSession::$lang, ['base'])->refer($key);
    }
    
    public static function indicator($key)
    {
        return Language::sole(servSession::$lang, ['base', 'indicator'])->refer($key);
    }

    public static function menu($key)
    {
        return Language::sole(servSession::$lang, ['base', 'menu'])->refer($key);
    }

    public static function dict($key)
    {
        return Language::sole(servSession::$lang, ['base', 'dict'])->refer($key);
    }

    public static function status($key)
    {
        return Language::sole(servSession::$lang, ['base', 'status'])->refer($key);
    }

}