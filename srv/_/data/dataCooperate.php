<?php


namespace _;


use Core\unitInstance;

class dataCooperate extends dataSole
{
    use unitInstance;

    const TABLE = 'cooperate';

    const PREFIX_WX_APPID = 'wx.appid.';

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($key, $name=null, array $config=[])
    {
        $data = [
            'key' => $key,
            'name' => $name ?: $key,
            'config' => json_encode($config, JSON_FORCE_OBJECT)
        ];
        $this->insert($data, ['config']);
        return $this->mysql->lastInsertId();
    }

    public function _inquireParse(array $row)
    {
        foreach ($row as $key => &$item) {
            switch ($key) {
                case 'config':
                    $item = json_decode($item, true);
                    break;
            }
        }
        return $row;
    }
}