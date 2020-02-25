<?php


namespace _;


use Core\unitInstance;

class servCooperate extends serv_
{
    use unitInstance;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function getWxaSource($appid)
    {
        $config = dataCooperate::sole($this->platform)->inquireOne([
            'key' => dataCooperate::PREFIX_WX_APPID . $appid,
        ], 'config->>"$.source" as source');
        return $config['source'];
    }

}