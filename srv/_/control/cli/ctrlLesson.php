<?php


namespace _\cli;


use _\data;
use _\dataLesson;
use _\dataLessonAccess;
use _\dataLessonRecord;
use _\servOrder;
use _\servRefund;
use _\servUser;
use Teacher\servLesson;

class ctrlLesson extends ctrl_
{
    public function _DO_create()
    {
        $tuid = \input::cli('tuid')->value(true);
        $title = \input::cli('title')->value(true);
        $price = \input::cli('price', 0)->value();
        $sn = uniqid(data::SN_LESSON);

        dataLesson::sole($this->platform)->insert([
            'sn' => $sn,
            'tuid' => $tuid,
            'title' => $title,
            'i_form' => 1,
            'i_step' => dataLesson::STEP_ONLIVE,
            'price' => $price,
        ]);
    }

    public function _DO_reset($opt)
    {
        switch ($opt) {
            case 'access':
                $lessonSn = \input::cli('lsn')->value(true);
                $usn  = \input::cli('usn')->value(true);
                $reason = \input::cli('reason', 'reset')->value();
                $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
                $uid = servUser::sole($this->platform)->usn2uid($usn);
                dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_RESET, [
                    "reason" => $reason
                ]);
                break;
        }
    }

    public function _DO_confirm()
    {
        $osn = \input::cli('osn')->value();
        $res = servOrder::sole($this->platform)->confirm($osn);
        var_dump ($res);
    }

    public function _DO_refund()
    {
        $tsn = \input::cli('tsn')->value();
        $usn = \input::cli('usn')->value();
        $reason = \input::cli('reason')->value();
        $srv = servRefund::sole($this->platform);
        if ($tsn[0] === 'L') {
            $srv->refundLesson($tsn, $usn, $reason);
        }
        if ($tsn[0] === 'S') {
            $srv->refundSeries($tsn, $usn, $reason);
        }
    }

    public function _DO_guest()
    {
        $lsn = \input::cli('lsn')->value();
        $usn = \input::cli('usn')->value();
        $res = servLesson::sole($this->platform)->inviteGuest($lsn, $usn);
        var_dump ($res);
    }

    public function _DO_export()
    {
        $lsn = \input::cli('lsn')->value(true);
        $dir = \input::cli('dir')->value(true);
        $len = \input::cli('len', 3)->toInt();
        $lid = servLesson::sole($this->platform)->sn2id($lsn);
        $rows = dataLessonRecord::sole($this->platform)->slice(dataLessonRecord::FORM_TIM, $lid, 0, 'next', -1);
        $file = 'null';
        foreach ($rows as $i => $row) {
            $id = str_pad($i+1, $len, '0', STR_PAD_LEFT);
            $content = json_decode($row['content'], true);
            $res = null;
            $name = servUser::sole($this->platform)->uid2profile($row['from_uid'])['name']."[$row[tms]]";
            foreach ($content as $item) {
                switch ($item['MsgType']) {
                    case 'TIMTextElem':
                        $res = "$name: ".$item['MsgContent']['Text'];
                        $file = "$id.txt";
                        break;
                    case 'TIMImageElem':
                        $url = $item['MsgContent']['ImageInfoArray'][0]['URL'];
                        $file = "$id.jpg";
                        $res = $url;
                        break;
                    case 'TIMCustomElem':
                        if ($item['MsgContent']['Desc'] == 'SOUND') {
                            $file = "$id.mp3";
                            $res = $item['MsgContent']['Data'];
                        }
                        if ($item['MsgContent']['Desc'] == 'QUOTE') {
                            $file = "$id.txt";
                            $res = "[quote]$name: ".$item['MsgContent']['Data'];
                        }
                        if ($item['MsgContent']['Desc'] == 'IMAGE') {
                            $file = "$id.jpg";
                            $res = $item['MsgContent']['Data'];
                        }
                        break;
                }
            }
            if (substr($res, 0, 5) == 'https') {
                $res = 'http'.substr($res, 5);
                $res = file_get_contents($res);
            }
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            file_put_contents("$dir/$file", $res);
        }
    }
}