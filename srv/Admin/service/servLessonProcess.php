<?php


namespace Admin;

use Core\unitInstance;

class servLessonProcess extends serv_
{
    use unitInstance;

    protected $data;
    const I_EVENT_MAP = [
        dataLessonProcess::EVENT_SUBMIT => 'submit',
        dataLessonProcess::EVENT_OPENED => 'opened',
        dataLessonProcess::EVENT_ONLIVE => 'onlive',
        dataLessonProcess::EVENT_REPOSE => 'repose',
        dataLessonProcess::EVENT_FINISH => 'finish',
        dataLessonProcess::EVENT_MODIFY => 'modify',
        dataLessonProcess::EVENT_DENIED => 'denied',
        dataLessonProcess::EVENT_CLOSED => 'closed',

    ];

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->data = dataLessonProcess::sole($this->platform);
    }


    public function list($lessonSn)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);

        $lists = $this->data->fetchAll(['lesson_id'=>$lessonId],'*',0);
        foreach ($lists as &$list) {
            $list['args'] = json_decode($list['args']);
            $list['i_event_map'] = self::I_EVENT_MAP[$list['i_event']];
        }
        return $lists;

    }

}