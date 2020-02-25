<?php


namespace _\stats;


use _\serv_;
use Core\unitInstance;

class servTeacher extends serv_
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

    public function varLesson($tuid)
    {
        $this->var($tuid, 'teacher.lesson.count');
    }

    public function varEnroll($tuid)
    {
        $this->var($tuid, 'teacher.enroll.unique');
    }

    public function varAccess($tuid, $new=true)
    {
        $this->var($tuid, 'teacher.access.count', 1);
        $this->var($tuid, 'teacher.access.unique', $new ? 1: 0);
    }

    public function varIncome($tuid, $amount)
    {
        $this->var($tuid, 'teacher.income.unique');
        $this->var($tuid, 'teacher.income.sum', $amount);
    }

    public function varRefund($tuid, $amount)
    {
        $this->var($tuid, 'teacher.refund.unique');
        $this->var($tuid, 'teacher.refund.sum', $amount);
    }

    public function flush()
    {
    }

    protected function var($tuid, $idx, $val=1)
    {
        $idx = servIdx::key2pos($idx);
        $DOMs = servDom::builds([
            servDom::ZONE_TEACHER => $tuid
        ]);
        foreach ($DOMs as $dom) {
            dataTimely::sole($this->platform)->var($dom, $idx, $val);
            dataDaily::sole($this->platform, 'now')->var($dom, $idx, $val);
        }
    }
}