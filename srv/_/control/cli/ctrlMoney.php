<?php


namespace _\cli;


use _\dataLesson;
use _\dataLessonSeries;
use _\dataMoney;
use _\dataOrder;
use _\dataUnionOrder;
use _\dataUserKeep;
use _\servMoney;
use _\servPayoff;
use _\servPromote;

class ctrlMoney extends ctrl_
{
    public function _DO_flush($opt)
    {
        switch ($opt) {
            case 'one':
                $uid = \input::cli('uid')->value(true);
                $res = servMoney::sole($this->platform)->flushUserKeep($uid);
                print_r($res);
                break;
            case 'all':
                $list = dataMoney::sole($this->platform)->fetchAll(null, "distinct(uid)", null, 0);
                foreach ($list as $uid) {
                    $res = servMoney::sole($this->platform)->flushUserKeep($uid);
                    echo $uid;
                    print_r($res);
                }
                break;
            default:
                echo "error" . LF;
                break;
        }
    }

    public function _DO_expect()
    {
        $srvPayoff = servPayoff::sole($this->platform);
        $daoOrder = dataOrder::sole($this->platform);
        $dateStart = \input::cli('dateStart')->value();
        $dateEnd = \input::cli('dateEnd')->value();
        $where= ['i_status>0'];
        if ($dateStart) {
            $where['dateStart >= ?'] = [$dateStart];
        }
        if ($dateEnd) {
            $where['dateEnd <= ?'] = [$dateEnd];
        }
        $orders = $daoOrder->fetchAll($where, '*');
        $data = [];
        foreach ($orders as $order) {
            $_data = $srvPayoff->calcExpect($order);
            foreach ($_data as $_uid => $_val) {
                $data[$_uid] = ($data[$_uid] ?? 0) + $_val;
            }
        }
        $daoUserKeep = dataUserKeep::sole($this->platform);
        foreach ($data as $uid => $expect) {
            $daoUserKeep->setAttr($uid, dataUserKeep::ITEM_MONEY, '$.expect', $expect);
        }
    }

    public function _DO_settle()
    {
        $hour = \input::cli('hour')->value();
        $res = servPayoff::sole($this->platform)->settlement($hour);
        print_r($res);
    }


}