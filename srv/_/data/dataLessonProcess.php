<?php
/**
 * 课程进程
 * 记录老师和管理员对课程的发布、审核、修改等日志
 */


namespace _;


use Core\unitInstance;

class dataLessonProcess extends dataLessonLog
{
    use unitInstance;

    const TABLE = 'lesson_process';

    const EVENT_SUBMIT = 0; // 提交课程
    const EVENT_OPENED = 1; // 审核通过
    const EVENT_ONLIVE = 2; // 直播上课
    const EVENT_REPOSE = 3; // 休息交流
    const EVENT_FINISH = 4; // 结束授课
    const EVENT_MODIFY = 5; // 修改课程(不需审核)
    const EVENT_DENIED = -1; // 审核被拒
    const EVENT_CLOSED = -2; // 使课程不可访问

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }
}