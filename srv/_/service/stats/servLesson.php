<?php


namespace _\stats;


use _\serv_;
use Core\unitInstance;

class servLesson extends serv_
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

    public function getSummary($lessonId)
    {
        $dom = servDom::build([
            servDom::ZONE_LESSON => $lessonId
        ]);
        $res = dataTimely::sole($this->platform)->get($dom);
        return servIdx::boost($res);
    }

    public function getEnroll($lessonId)
    {
        $idx = servIdx::key2pos('lesson.enroll.unique');
        $DOM = servDom::build([
            servDom::ZONE_LESSON => $lessonId
        ]);
    }

    public function varBrowse($lessonId, $originId=null)
    {
        $idx = servIdx::key2pos('lesson.browse.unique');

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, [$idx => 1]);
    }

    public function varEnroll($lessonId, $originId = null)
    {
        $idx = servIdx::key2pos('lesson.enroll.unique');

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, [$idx => 1]);
        $tuid = \_\servLesson::sole($this->platform)->id2tuid($lessonId);
        servTeacher::sole($this->platform)->varEnroll($tuid);
    }

    public function varAccess($lessonId, $originId = 0, $new = true)
    {
        $IDXs = [
            servIdx::key2pos('lesson.access.count') => 1,
            servIdx::key2pos('lesson.access.unique') => $new ? 1 : 0,
        ];

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, $IDXs);

        $tuid = \_\servLesson::sole($this->platform)->id2tuid($lessonId);
        servTeacher::sole($this->platform)->varAccess($tuid, $new);
    }

    public function getIncome($lessonId, $originId=null)
    {
        $pos = servIdx::key2pos('lesson.income.sum');
        $doms = [
            servDom::ZONE_LESSON => $lessonId
        ];
        if ($originId) {
            $doms[servDom::ZONE_ORIGIN] = $originId;
        }
        $dom = servDom::build($doms);
        $val = dataTimely::sole($this->platform)->get($dom, $pos);
        return intval($val)/100;
    }

    public function varIncome($lessonId, $originId = null, $amount)
    {
        $IDXs = [
            servIdx::key2pos('lesson.income.unique') => 1,
            servIdx::key2pos('lesson.income.sum') => $amount,
        ];

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, $IDXs);

        $tuid = \_\servLesson::sole($this->platform)->id2tuid($lessonId);
        servTeacher::sole($this->platform)->varIncome($tuid, $amount);
    }

    public function varRefund($lessonId, $originId = null, $amount = 0)
    {
        $IDXs = [
            servIdx::key2pos('lesson.refund.unique') => 1,
            servIdx::key2pos('lesson.refund.sum') => $amount,
        ];

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, $IDXs);

        $tuid = \_\servLesson::sole($this->platform)->id2tuid($lessonId);
        servTeacher::sole($this->platform)->varRefund($tuid, $amount);
    }

    public function varRate($lessonId, $originId=null, $score, $count=1)
    {
        $IDXs = [
            servIdx::key2pos('lesson.rate.count') => $count,
            servIdx::key2pos("lesson.rate-s{$score}.count") => $count,
            servIdx::key2pos('lesson.rate.sum') => $score * $count,
        ];

        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);

        $this->cross($DOMs, $IDXs);
    }

    public function varPayoff($lessonId, $originId=null, $amount=0)
    {
        $IDXs = [
            servIdx::key2pos('lesson.payoff.sum') => intval($amount)
        ];
        $DOMs = servDom::builds([
            servDom::ZONE_LESSON => $lessonId,
            servDom::ZONE_ORIGIN => $originId,
        ]);
        $this->cross($DOMs, $IDXs);
    }


    protected function cross($DOMs, $IDXs)
    {
        $timely = dataTimely::sole($this->platform);
        $daily = dataDaily::sole($this->platform, 'now');
        foreach ($DOMs as $dom) {
            foreach ($IDXs as $idx => $val) {
                $timely->var($dom, $idx, $val);
                $daily->var($dom, $idx, $val);
            }
        }
    }
}