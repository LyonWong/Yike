<?php


namespace Admin;


class wdgtMetronic
{
    /**
     * @param array $data [<key, name, type, path, next, more>, ..]
     * @param null $cursor
     * @return string
     */
    public static function menu(array $data, $cursor = null)
    {
        $html = '';
        foreach ($data as $item) {
            $isActive = strpos($cursor, $item['key']) === 0;
            $aclass = $isActive ? 'active open' : '';
            $name = wdgtLang::menu($item['name']);
            $html .= "<li class='$aclass'><a href='$item[path]' data-key='$item[key]'><i class='fa icon-$item[key]'></i>";
            $html .= "<span class='title'>$name</span>";
            if (isset ($item['next'])) {
                $html .= "<span class='arrow $aclass'></span></a>";
                $html .= self::submenu($item['next'], $cursor);
            } else {
                $html .= "</a>";
            }
            $html .= "</li>";
        }
        return $html;
    }

    public static function submenu($subs, $cursor)
    {
        $html = "<ul class='sub-menu'>";
        foreach ($subs as $item) {
            $aclass = '';
            $name = wdgtLang::menu($item['name']);
            if (strpos($cursor, $item['key']) === 0) {
                $aclass .= 'active open';
            }
            if ($cursor == $item['key']) {
                $aclass .= ' current';
            }
            if (isset($item['next'])) {
                $html .= "<li class='$aclass'><a href='$item[path]' data-key='$item[key]'>
                <i class='fa icon-$item[key]'></i>$name
                <span class='arrow $aclass'></span></a>";
                $html .= self::submenu($item['next'], $cursor);
                $html .= '</li>';
            } elseif (isset($item['more'])) {
                $html .= "<li class='$aclass sub-more'><a href='$item[path]' data-key='$item[key]'>
                <i class='fa icon-$item[key]'></i>$name
                <span class='arrow $aclass'></span></a>";
                $html .= self::submenu($item['more'], $cursor);
                $html .= "</li>";
            } else {
                $html .= "<li class='$aclass'><a href='$item[path]' data-key='$item[key]'><i class='fa icon-$item[key]'></i>$name</a></li>";
            }
        }
        $html .= "</ul>";
        return $html;
    }

}