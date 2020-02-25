<?php


namespace _;


use _\weixin\serv;
use Core\library\Http;
use Core\unitInstance;

class servContentSecurity extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function checkArticle($content)
    {
        $seperator = ",.!?;|，。！？； \n";
        $phrases = explode("|", preg_replace("/[$seperator]/u", '|', $content)); // 统一分隔符，拆解为短语
        $len = 0; // 每次检测不超过500字
        $idx = [];
        $_i = $i = 0;
        foreach ($phrases as $i => $phrase) {
            $len+=strlen($phrase);
            if ($len>=500) { // 每次检测的文本总长度不超过500
                $len = 0;
                $this->filterText($phrases, $idx, $_i, $i);
                $_i = $i;
            }
            $idx[$i] = true; // 预设内容都不安全
        }
        $this->filterText($phrases, $idx, $_i, $i);

        $res = array_filter($idx);
        foreach ($res as $i => &$v) {
            $v = $phrases[$i];
        }
        return array_filter($res);
    }

    /**
     * 标记安全的内容位置
     * @param array $arr 待检测的内容数组
     * @param array $idx 作为标记的指针数组
     * @param $start
     * @param $end
     */
    public function filterText($arr, &$idx, $start, $end)
    {
        $slice = array_slice($arr, $start, $end - $start + 1);
        $piece = implode(',', $slice);
        if ($slice) {
            $res = serv::sole($this->platform)->textSecCheck($piece);
        } else {
            $res = false;
        }
        if ($res) { // 有异常
            $p = round(($start + $end) / 2);
            if ($end > $start) {
                $this->filterText($arr, $idx, $start, $p - 1);
                $this->filterText($arr, $idx, $p, $end);
            }
        } else { // 无异常的片段标记
            for ($i = $start; $i <= $end; $i++) {
                $idx[$i] = false;
            }
        }
    }


    public function checkImage($url)
    {
        $fp = tmpfile();
        fwrite($fp, Http::inst()->get($url));
        $file = stream_get_meta_data($fp)['uri'];
        return serv::sole($this->platform)->imageSecCheck($file);
    }

}