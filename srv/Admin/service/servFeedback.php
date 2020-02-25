<?php


namespace Admin;


use _\dataTicket;
use _\view;
use Core\unitInstance;

class servFeedback extends serv_
{
    use unitInstance;

    const STATUS_MAP = [
        dataTicket::STATUS_START => 'start',
        dataTicket::STATUS_PENDING => 'pending',
        dataTicket::STATUS_CLOSE => 'clode',

    ];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function getList()
    {
        $rets = dataTicket::sole($this->platform)->fetchAll(['i_type'=>dataTicket::TYPE_FEEDBACK],'*');
        $return = [];
        foreach ($rets as $ret) {
            $content = json_decode($ret['content'],true);
            $ret['text'] = $content['text'];
            $ret['image'] = $content['image'] ?? '';
            unset($ret['content']);
            $ret['name'] = dataUser::sole($this->platform)->fetchOne(['id'=>$ret['uid']],'name','name');
            $return[] = $ret;
        }

        return $return;

    }

}