<?php


namespace Admin;


use _\dataSettings;
use Core\unitInstance_;

class servSettings extends \_\servSettings
{
    use unitInstance_;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function create($type, $item, $remark, $datum)
    {
        $data = [
            'i_type' => array_search($type, self::TYPE_MAP),
            'item' => $item,
            'remark' => $remark,
            'datum' => json_encode(json_decode($datum), JSON_UNESCAPED_UNICODE),
        ];
        $id = dataSettings::sole($this->platform)->append($data);
        return $id;
    }

    public function modify($id, $type, $item, $remark, $datum)
    {
        $data = [
            'i_type' => array_search($type, self::TYPE_MAP),
            'item' => $item,
            'remark' => $remark,
            'datum' => json_encode(json_decode($datum), JSON_UNESCAPED_UNICODE),
        ];
        return dataSettings::sole($this->platform)->update($data, ['id' => $id])->rowCount();
    }

    public function detail($id)
    {
        $res = dataSettings::sole($this->platform)->fetchOne(['id' => $id], '*');
        $res['datum'] = json_decode($res['datum'], true);
        return $res;
    }

}