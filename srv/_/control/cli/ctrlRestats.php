<?php


namespace _\cli;


use _\dataOrder;
use _\dataRating;
use _\servUser;
use _\stats\dataTimely;
use _\stats\servDom;
use _\stats\servIdx;

class ctrlRestats extends ctrl_
{
    public function _DO_rating()
    {
        $lessonId = \input::cli('lesson_id')->value(true);
        $daoRating = dataRating::sole($this->platform);
        $res = $daoRating->fetchAll(
            ['lesson_id' => $lessonId, 'i_status>=0'],
            ['suid', 'score']
        );
        $stats = [];
        foreach ($res as $item) {
            $originId = dataOrder::sole($this->platform)->fetchOne(
                ['lesson_id' => $lessonId, 'uid' => $item['suid'], 'i_status>0'],
                'origin_id',
                0
            );
            if (!$originId) {
                $originId = servUser::sole($this->platform)->uid2origin($item['suid']);
            }
            foreach (['rate', "rate-s$item[score]"] as $star) {
                foreach (['*', $originId] as $o) {
                    $stats[$o]["lesson.$star.count"] = ($stats[$o]["lesson.$star.count"] ?? 0) + 1;
                    $stats[$o]["lesson.$star.sum"] = ($stats[$o]["lesson.$star.sum"] ?? 0) + $item['score'];
                }
            }
        }
        foreach ($stats as $o => $item) {
            if ($o == '*') {
                $dom = servDom::build([
                    servDom::ZONE_LESSON => $lessonId,
                ]);
            } else {
                $dom = servDom::build([
                    servDom::ZONE_LESSON => $lessonId,
                    servDom::ZONE_ORIGIN => $o,
                ]);
            }
            foreach ($item as $idx => $val) {
                $data = [
                    'dom' => $dom,
                    'idx' => servIdx::key2pos($idx),
                    'val' => $val,
                ];
                dataTimely::sole($this->platform)->insert($data, ['val']);
            }
        }
    }

    public function _DO_data()
    {
        $where = ['i_status>0'];
        $orders = dataOrder::sole($this->platform)->fetchAll($where, '*');
        $data = [];
        foreach ($orders as $order) {
            $idxes = [
                'lesson.payoff.sum' => $order['payoff_amount'],
                'lesson.income.sum' => $order['order_amount'],
            ];
            $doms = servDom::build([
                servDom::ZONE_LESSON => $order['lesson_id'],
                servDom::ZONE_ORIGIN => $order['origin_id']
            ]);
            foreach ($doms as $d) {
                foreach ($idxes as $i => $v) {
                    $data[$d][$i] = ($data[$d] ?? 0) + $v;
                }
            }
        }
        $dao = dataTimely::sole($this->platform);
        foreach ($data as $d => $item) {
            foreach ($item as $i => $v) {
                echo "$d|$i|$v\n";
                $idx = servIdx::key2pos($i);
                $dao->set($d, $idx, $v);
            }
        }
    }

}