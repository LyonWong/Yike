<?php


namespace Teacher;


use Core\library\Tool;
use Core\unitInstance;
use _\servQiniu;

class servApply extends serv_
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

    public function apply($tuid, \_\unitTeacherDatum $teacherDatum)
    {
        $ret = dataTeacher::sole($this->platform)->fetchOne(['tuid' => $tuid], 'datum', 'datum');
        if ($ret) {
            $datum = json_decode($ret, true);
            $datum['about'] = $teacherDatum->about;
            dataTeacher::sole($this->platform)->update(['datum' => json_encode($datum), 'i_status' => dataTeacher::STATUS_CREDIBLE], ['tuid' => $tuid]);
        } else {
            \_\servTeacher::sole($this->platform)->apply($tuid, $teacherDatum);
            dataTeacher::sole($this->platform)->update(['i_status' => dataTeacher::STATUS_CREDIBLE], ['tuid' => $tuid]);
            $unitLesson = new \_\unitLesson();
            $unitLesson->title = '试讲课程';
            $unitLesson->iForm = dataLesson::FORM_TRY;
            $unitLesson->dtmStart = date('Y-m-d H:i:s');
            $unitLesson->duration = 2;
            $unitLesson->brief = '这是为您默认创建的试讲课程，可在此体验熟悉我们的授课操作。试讲课程对外不可见，但您可以分享给好友一同体验';
            $unitLesson->category = '';
            $unitLesson->tags = '{}';
            $unitLesson->price = 0;
            $unitLesson->quota = 0;
            $unitLesson->cover = 'lesson/cover/tryLesson';
            $lessonSn = servLesson::sole($this->platform)->create($tuid, $unitLesson, false);
            if ($lessonSn) {
                dataLesson::sole($this->platform)->update(['i_step' => dataLesson::STEP_OPENED], ['sn' => $lessonSn]);
            }

        }

    }

    public function submit($tuid, $name, $email, $telephone)
    {
        $ret = \_\dataUser::sole($this->platform)->fetchOne(['id' => $tuid], 'info', 'info');
        if ($ret) {
            $ret = json_decode($ret, true);
            $ret['name'] = $name;
            $ret['email'] = $email;
            $ret['telephone'] = $telephone;
            $tim = \_\servTIM::sole($this->platform)->tim();
            $tim->account_import(servUser::sole($this->platform)->uid2usn($tuid), $name, '');
            return \_\dataUser::sole($this->platform)->update(['info' => json_encode($ret), 'name' => $name, 'tms_update' => date('Y-m-d H:i:s')], ['id' => $tuid]);
        }
        return false;
    }

    public function putFile($tuid, $tmpName)
    {
        $usn = servUser::sole($this->platform)->uid2usn($tuid);
        return servQiniu::inst()->putFile('user/' . $usn . '/avatar', $tmpName);
    }

    public function killCookies()
    {
        $key = 'KILL_COOKIE' . Tool::getClientIp(true);
        if (!data::redis()->get($key)) {
            return true;
        }
        return false;

    }

}
