<?php


namespace _\cli;


use _\servPromote;
use _\unitLessonPromote;

class ctrlPromote extends ctrl_
{
    public function _DO_create($type)
    {
        $iType = array_search($type, servPromote::TYPE_MAP);
        $unitPromote = unitLessonPromote::inst($iType);
        $targetSn = \input::cli('tsn')->value();
        $uid = \input::cli('uid')->value(true);
        $originId = \input::cli('oid')->value();
        $unitPromote->discount = \input::cli('discount')->toInt();
        $unitPromote->commission = \input::cli('commission')->toInt();
        $unitPromote->payoff = \input::cli('payoff')->toInt();
        $unitPromote->quantity = \input::cli('quantity')->value();
        $unitPromote->expire = \input::cli('expire')->value();
        $unitPromote->duration = \input::cli('duration')->value();
        $sn = servPromote::sole($this->platform)->create($uid, $targetSn, $originId, $unitPromote);
        echo "PromoteSN: $sn".LF;
    }

    public function _DO_attr()
    {
        $sn = \input::cli('sn')->value();
        $res = servPromote::sole($this->platform)->attr($sn);
        print_r($res);
    }

    public function _DO_check()
    {
        $lessonId = \input::cli("lid", 0)->value();
        $seriesId = \input::cli('sid', 0)->value();
        $uid = \input::cli('uid')->value(true);
        $res = servPromote::sole($this->platform)->check($uid, $lessonId, $seriesId);
        var_dump ($res);
    }

    public function _DO_draw()
    {
        $promoteSn = \input::cli("psn")->value(true);
        $uid = \input::cli('uid')->value(true);
        $res = servPromote::sole($this->platform)->draw($promoteSn, $uid);
        var_dump ($res);
    }

    public function _DO_quota($opt)
    {
        switch($opt) {
            case 'set':
                $psn = \input::cli('psn')->value(true);
                $quantity = \input::cli('quantity')->value(true);
                $expire = \input::cli('expire')->value(true);
                $batch = \input::cli('batch', 1)->toInt();
                $prefix = \input::cli('prefix', "QuotaSn: ")->value();
                while ($batch--) {
                    $qsn = servPromote::sole($this->platform)->setQuota($psn, $quantity, $expire);
                    echo $prefix.$qsn.LF;
                }
                break;
            case 'use':
                $qsn = \input::cli("qsn")->value(true);
                $uid = \input::cli('uid')->value(true);
                $res = servPromote::sole($this->platform)->useQuota($qsn, $uid);
                var_dump($res);
                break;
        }
    }

    public function _DO_slice()
    {
        $psn = \input::cli('psn')->value(true);
        $cursor = \input::cli('cursor', 0)->value();
        $limit = \input::cli('limit', 1)->toInt();
        $clip = servPromote::sole($this->platform)->slice($psn, $cursor, $limit);
        print_r($clip);
    }

    public function _DO_convey()
    {
        $sn = \input::cli('sn')->value(true);
        $token = servPromote::sole($this->platform)->convey($sn);
        echo "token: $token".LF;
    }

}