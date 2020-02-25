<?php


namespace Admin;


use _\dataBlock;
use _\servCache;
use _\weixin\serv;
use Core\unitInstance_;

class servBlock extends \_\servBlock
{
    use unitInstance_;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($data)
    {
        $data['i_type'] = array_search($data['type'], self::TYPE_MAP);
        unset($data['type']);
        dataBlock::sole($this->platform)->insert($data, ['name', 'i_type', 'weight', 'extra']);
        servCache::sole($this->platform)->del(servCache::TAG_LESSON_HOME.servBlock::TYPE_MAP[$data['i_type']]);
    }

    public function modify($key, $data)
    {
        if (isset($data['type'])) {
            $data['i_type'] = array_search($data['type'], self::TYPE_MAP);
            unset($data['type']);
        }
        $update = dataBlock::sole($this->platform)->update($data, ['key' => $key])->rowCount();
        $iType = dataBlock::sole($this->platform)->fetchOne(['key' => $key], 'i_type', 0);
        servCache::sole($this->platform)->del(servCache::TAG_LESSON_HOME.servBlock::TYPE_MAP[$iType]);
        return boolval($update);
    }

}