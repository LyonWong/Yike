<?php


namespace _;


use Core\unitInstance;

class servNotice extends serv_
{
    use unitInstance;

    protected $WxMpMsg;

    protected $DAO;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        $this->WxMpMsg = servMpMsg::sole($this->platform);
        $this->DAO = dataNotice::sole($this->platform);
        parent::__construct($platform);
    }

    public function enroll($lessonSn, $uid)
    {
        $this->WxMpMsg->sendEnrollMsg($lessonSn,$uid);
        $this->DAO->append(dataNotice::TYPE_ENROLL, $uid, ['lesson_sn'=> $lessonSn]);
    }

}