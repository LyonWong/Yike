<?php


namespace _;



use Teacher\unitLesson;
use Teacher\unitTeacherDatum;

class settingTest extends \PHPUnit_Framework_TestCase
{
    private $platform;
    private $mysql;

    private $uid;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mysql = data::mysql();
    }

    public function setup()
    {
        parent::setUp();
        $this->uid = dataUser::sole($this->platform)->append(4, 'student', 0);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->mysql->run("truncate table lesson_board");
    }


    public static function tearDownAfterClass()
    {
        $mysql = data::mysql();
        $tables = $mysql->run("show tables")->fetchAll(null, 0);
        foreach ($tables as $table) {
            $mysql->run("truncate table `$table`");
        }
    }


    public function testUid2Setting()
    {
        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::NOTICE_PAYOFF);
        $this->assertEquals(1,$noticePayoffSetting, "notice.payoff");

        servUser::sole(null)->userSet($this->uid,dataUser::NOTICE_PAYOFF,0);
        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::NOTICE_PAYOFF);
        $this->assertEquals(0,$noticePayoffSetting, "notice.payoff");

        servUser::sole(null)->userSet($this->uid,dataUser::NOTICE_PAYOFF,1);
        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::NOTICE_PAYOFF);
        $this->assertEquals(1,$noticePayoffSetting, "notice.payoff");


        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::AUTO_REFUND);
        $this->assertEquals(1,$noticePayoffSetting, "auto_refund");

        servUser::sole(null)->userSet($this->uid,dataUser::AUTO_REFUND,0);
        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::AUTO_REFUND);
        $this->assertEquals(0,$noticePayoffSetting, "auto_refund");

        servUser::sole(null)->userSet($this->uid,dataUser::AUTO_REFUND,1);
        $noticePayoffSetting = servUser::sole(null)->uid2setting($this->uid, dataUser::AUTO_REFUND);
        $this->assertEquals(1,$noticePayoffSetting, "auto_refund");



    }


}
