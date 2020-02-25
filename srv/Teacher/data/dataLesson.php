<?php


namespace Teacher;


use Core\unitInstance_;

class dataLesson extends \_\dataLesson
{
    use unitInstance_;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function append($tuid, \_\unitLesson $unitLesson)
    {
        $plan = [
            'dtm_start' => $unitLesson->dtmStart,
            'duration' => $unitLesson->duration,
        ];
        $extra = [
          'cover' => $unitLesson->cover,
        ];
        $data = [
            'sn' => $this->uniqueSN(self::SN_LESSON),
            'tuid' => $tuid,
            'title' => $unitLesson->title,
            'brief' => $unitLesson->brief,
            'category' => $unitLesson->category,
            'tags' => $unitLesson->tags,
            'i_form' => $unitLesson->iForm,
            'price' => $unitLesson->price,
            'quota' => $unitLesson->quota,
            'homework' => '{}',
            'plan' => json_encode($plan),
            'i_step' => dataLesson::STEP_SUBMIT,
            'extra' => json_encode($extra),
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }
}