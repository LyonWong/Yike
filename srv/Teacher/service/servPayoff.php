<?php


namespace Teacher;


use _\dataMoney;
use _\dataUserKeep;
use _\servMoney;
use _\wdgtLang;
use Core\unitInstance;

class servPayoff extends \_\servPayoff
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }



    public function record($uid, $item, $page, $limit)
    {
        $filter = [
            'uid' => $uid,
        ];
        if ($item) {
            $dict = array_flip(servMoney::ITEM_MAP);
            $filter['i_item'] = $dict[$item];
        }
        $data = dataMoney::sole($this->platform)->paging(
            $page,
            $limit,
            $filter,
            ['i_item', 'tms', 'amount', 'args'],
            "tms desc"
        );
        foreach ($data['pages'] as &$page) {
            $page['amount'] /= 100;
            $page['args'] = json_decode($page['args'], true);
            $page['item'] =  servMoney::ITEM_MAP[$page['i_item']];
            $page['desc'] = wdgtLang::dict($page['item']);
            unset($page['i_item']);
        }
        return $data;
    }

}