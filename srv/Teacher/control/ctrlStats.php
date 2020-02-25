<?php


namespace Teacher;


class ctrlStats extends ctrlSess
{
    const ERR_NOT_YOUR_LESSON = [1, 'not your lesson'];

    public function _DO_overview()
    {
        $data = servStats::sole($this->platform)->overview($this->uid);
        $this->apiSuccess($data);
    }
    public function _DO_lesson()
    {
        $data = servStats::sole($this->platform)->lesson($this->uid);
        $this->apiSuccess($data);
    }

    public function _DO_trends()
    {}

    public function _DO_origin()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $originId = $this->apiGET('origin_id', 0);
        if (!servLesson::sole($this->platform)->checkSpeak($lessonSn, $this->usn)) {
            $this->apiFailure(self::ERR_NOT_YOUR_LESSON);
        }
        $data = servStats::sole($this->platform)->origin($lessonSn, $originId);
        $this->apiSuccess($data);
    }

}