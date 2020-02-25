<?php


namespace _;


use Core\unitFile;
use Core\unitInstance;

class servDeploy extends serv_
{
    use unitInstance;
    use unitFile;

    const FILE = 'deploy';

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function info(&$cursor=0)
    {
        $file = config::load('boot', 'path', 'runtime').'/'.self::FILE;
        $res = file_exists($file);
        if ($res) {
            $fp = fopen($file, 'r');
            fseek($fp, $cursor);
            $info = [];
            while ($row = fgets($fp)) {
                $info[] = $row;
            }
            $cursor = ftell($fp);
            return $info;
        } else {
            return false;
        }

    }

}