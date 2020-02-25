<?php


namespace Teacher;


use _\servTrigger;
use Core\unitInstance;

class servLessonPrepare extends \_\servLessonPrepare
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

    public function releaseView($lessonSn)
    {
        $rows = $this->slicePreview($lessonSn, '-', data::TOWARD_NEXT, -1);
        $srvLesson = servLesson::sole($this->platform);
        $profile = $srvLesson->profile($lessonSn);
        if ($profile['step'] !== servLesson::STEP_MAP[dataLesson::STEP_OPENED]) {
            return false;
        }
        $fromUsn = $profile['teacher']['sn'];
        foreach ($rows as $row) {
            $srvLesson->createRecord($lessonSn, $fromUsn, servLesson::RECORD_FORM_MAP[dataLesson::FORM_VIEW], json_encode($row['content']));
        }
        servTrigger::sole($this->platform)->onLessonOpen($lessonSn);
        servTrigger::sole($this->platform)->onLessonFinish($lessonSn);
        $lessonId = $srvLesson->sn2id($lessonSn);
        dataLesson::sole($this->platform)->update([
            'i_step' => dataLesson::STEP_FINISH
        ], ['id' => $lessonId]);
        return true;
    }
}