<?php


namespace Core\library;


use Core\unitFile;
use Core\unitInstance;

class Language
{
    use unitInstance;
    use unitFile;

    protected $lang;

    protected $dict = [];

    protected $undefined = [];
    
    protected $section;

    protected $note;
    
    const MAX_KEY_LEN = 32;
    const REF_KEY_LEN = 12;

    const ALIAS = [
        'zh' => 'zh-cn',
        'zh-CN' => 'zh-cn',
        'zh-TW' => 'zh-tw',
    ];

    public function __construct($lang, array $sections)
    {
        $this->lang = empty(self::ALIAS[$lang]) ? $lang : self::ALIAS[$lang];
        foreach ($sections as $section) {
            $this->dict = array_merge($this->dict, \config::load('lang/' . $this->lang, $section, null, []));
        }
        $this->section = implode(',', $sections);
        $this->note = "lang-$lang.undefined.ini";
        if ($file = $this->fileCheck($this->note)) {
            $this->undefined = parse_ini_file($file);
        }
    }

    /**
     * @param $lang
     * @param array $sections
     * @return self
     */
    public static function sole($lang, array $sections)
    {
        return self::_singleton($lang, $sections);
    }

    public static function call($lang, array $sections)
    {
        $inst = self::sole($lang, $sections);
        return function($key) use ($inst) {
            return $inst->refer($key);
        };
    }

    public static function detect()
    {
        $langs = \config::load('boot', 'public', 'language.support');
        if (preg_match("/$langs/i", $_SERVER['HTTP_ACCEPT_LANGUAGE']??'-', $matches)) {
            return $matches[0];
        } else {
            return \config::load('boot', 'public', 'language.default');
        }
    }

    public function refer($key)
    {
        if (strlen($key) > self::MAX_KEY_LEN) {
            $_key = substr($key, 0, self::REF_KEY_LEN).'.'.md5($key);
        } else {
            $_key = $key;
        }
        if (isset($this->dict[$_key])) {
            return $this->dict[$_key];
        } else {
            if (empty($this->undefined[$_key]) && $_key) {
                $text = $key == $_key ? '' : "; $key\n";
                $text .= "$_key = ; [$this->section]\n";
                $this->fileWrite($this->note, $text, FILE_APPEND);
                $this->undefined[$_key] = '';
            }
            return $key;
        }
    }

    public static function file($lang, $path)
    {
        $lang = empty(self::ALIAS[$lang]) ? $lang : self::ALIAS[$lang];
        $filepath = PATH_SPACE."/file/lang/$lang/$path";
        if ($realpath = realpath($filepath)) {
            return file_get_contents($realpath);
        } else {
            return "Language file `$lang/$path` does not exist.";
        }
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return array
     */
    public function getDict()
    {
        return $this->dict;
    }


}