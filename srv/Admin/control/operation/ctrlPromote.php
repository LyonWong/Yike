<?php


namespace Admin\operation;


use _\dataLessonAccess;
use _\servOrders;
use _\servOrigin;
use _\unitLessonPromote;
use Admin\servLessonAccess;
use Admin\servPromote;
use Admin\servUser;
use Admin\unitQueryPromote;

class ctrlPromote extends ctrl_
{
    protected $scopeKey = 'operation-promote';

    public function _DO_()
    {
        $query = unitQueryPromote::init($_GET);
        $query->dateStart = \input::get('dateStart', '-15 days')->toDate();
        $query->dateEnd = \input::get('dateEnd', 'today')->toDate();
        \view::tpl('page', [
            'page' => 'operation/promote',
            'type_map' =>servPromote::TYPE_MAP,
            'query' => $query,
        ]);
    }

    public function _GET_rule()
    {
        $query = unitQueryPromote::init($_GET);
        $length = \input::get('length')->toInt();
        $start = \input::get('start')->toInt();
        $draw = \input::get('draw')->toInt();
        $res = servPromote::sole($this->platform)->page($query, round(($start / $length) + 1), $length);
        $srvOrigin = servOrigin::sole($this->platform);
        foreach ($res['pages'] as &$item) {
            $item = [
                $item['sn'],
                $item['remark'],
                $item['i_type'],
                $item['i_status'],
                $item['uid'],
                $item['origin_id'] .'#'. $srvOrigin->profile($item['origin_id'])['name'],
                $item['discount']/100,
                $item['commission']/100,
                $item['expire']??'',
                $item['tms_create'],
                '<a href="#" class="btn btn-default btn-release" data-psn="'.$item['sn'].'" data-mode="server" data-toggle="modal" data-target="#myModal2">创建临时券</a>'
                .'<a href="./promote-report?psn='.$item['sn'].'" class="btn btn-default">使用情况</a>',
            ];
        }

        $data = [
            'draw' => $draw + 1,
            'recordsTotal' => $res['total'],
            'recordsFiltered' => $res['total'],
            'data' => $res['pages']
        ];
        echo json_encode($data);
    }


    public function _POST_create()
    {
        $type = \input::post('type')->value();
        $iType = array_search($type, servPromote::TYPE_MAP);
        $unitPromote = unitLessonPromote::inst($iType);
        $targetSn = \input::post('tsn')->value();
        $uid = \input::post('uid')->value(true);
        $originId = \input::post('oid')->value();
        $unitPromote->discount = \input::post('discount')->toInt();
        $unitPromote->commission = \input::post('commission')->toInt();
        $unitPromote->payoff = \input::post('payoff')->toInt();
        $unitPromote->quantity = \input::post('quantity')->value();
        $unitPromote->expire = \input::post('expire')->value();
        $unitPromote->duration = \input::post('duration')->value();
        $sn = servPromote::sole($this->platform)->create($uid, $targetSn, $originId, $unitPromote);
        if($sn) {
            $this->apiSuccess();
        }
        $this->apiFailure(self::ERR_UNDEFINED);
    }

    public function _POST_setQuota()
    {
        $psn = \input::post('psn')->value(true);
        $quantity = \input::post('quantity')->value(true);
        $expire = \input::post('expire')->value(true);
        $batch = \input::post('batch', 1)->toInt();
        $prefix = \input::post('prefix', "QuotaSn: ")->value();
        $text = '';
        while ($batch--) {
            $qsn = servPromote::sole($this->platform)->setQuota($psn, $quantity, $expire);
            $text.= $prefix.$qsn."<br>";
        }
        $this->apiSuccess($text);
    }

    public function _GET_report()
    {
        $psn = \input::get('psn')->value();
        $pinfo = servPromote::sole($this->platform)->info($psn);
        $origin = servOrigin::sole($this->platform)->tier($pinfo['origin_id']);
        $srvAccess = servLessonAccess::sole($this->platform);
        $srvUser = servUser::sole($this->platform);
        if ($pinfo) {
            $uids = servPromote::sole($this->platform)->queryReceivedUids($psn);
        } else {
            $uids = [];
        }
        $data = [];
        $total = [];
        foreach ($uids as $uid) {
            $_report = $srvAccess->reportOnLesson($pinfo['lesson_id'], $uid);
            $_data = [
                'user' => $srvUser->uid2profile($uid),
            ];
            foreach ([
                dataLessonAccess::EVENT_RECEIVE,
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS,
                dataLessonAccess::EVENT_REFUND,
                     ] as $event) {
                if (isset($_report[$event])) {
                    $_report[$event]['args'] = json_decode($_report[$event]['args'], true);
                    $total[servLessonAccess::ACCESS_MAP[$event]] = ($total[servLessonAccess::ACCESS_MAP[$event]] ?? 0) + 1;
                } else {
                    $_report[$event] = [
                        'tms' => null,
                        'args' => null,
                    ];
                }
                $_data[servLessonAccess::ACCESS_MAP[$event]] = $_report[$event] ?? [];
            }
            $data[] = $_data;
        }
        \view::tpl('page', [
            'page' => 'operation/promote-report',
            'total' => $total,
            'origin' => $origin,
            'data' => $data,
            'query' => [
                'psn' => $psn
            ]
        ]);
    }
}