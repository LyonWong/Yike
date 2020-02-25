<?php


namespace Core\library;

require_once PATH_ROOT.'/library/clsHttp.php';

class Http extends \clsHttp
{
    public static function inst(array $options = [])
    {
        $inst = new self($options);
        return $inst;
    }

    protected function log($content)
    {
        \output::debug('http', $content, DEBUG_SLOT_CORE, DEBUG_REPORT_LOG);
    }

}