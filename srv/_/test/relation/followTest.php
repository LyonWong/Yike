<?php


namespace _;



use Teacher\unitLesson;

class followTest extends \PHPUnit_Framework_TestCase
{
    private $platform;
    private $mysql;
    private $srv;
    private $dao;

    private $tuid;
    private $uid;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mysql = data::mysql();
        $this->srv = servRelation::sole($this->platform);
        $this->dao = dataRelation::sole($this->platform);
    }

    public function setup()
    {
        parent::setUp();
        $this->tuid = \Teacher\dataUser::sole($this->platform)->append(3, 'teacher', 0);
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



    public function testFollow()
    {

        $res = $this->srv->follow($this->uid, $this->tuid);
        $this->assertSame(['isFollow' => true], $res, "follow");

        $ret = $this->srv->isFollow($this->uid, $this->tuid);
        $this->assertTrue($ret,'follow');

        $res = $this->srv->follow($this->uid, $this->tuid);
        $this->assertSame(['isFollow' => false], $res, 'cancel follow');

        $ret = $this->srv->isFollow($this->uid, $this->tuid);
        $this->assertFalse($ret,'unfollow');

        $res = $this->srv->follow($this->uid, $this->tuid);
        $this->assertSame(['isFollow' => true], $res, "follow");

        $ret = $this->srv->isFollow($this->uid, $this->tuid);
        $this->assertTrue($ret,'follow');
    }


}
