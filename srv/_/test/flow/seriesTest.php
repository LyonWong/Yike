<?php


namespace _;



use Teacher\unitLesson;

class seriesTest extends \PHPUnit_Framework_TestCase
{
    private $platform;
    private $mysql;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mysql = data::mysql();
    }


    public static function tearDownAfterClass()
    {
        $mysql = data::mysql();
        $tables = $mysql->run("show tables")->fetchAll(null, 0);
        foreach ($tables as $table) {
            $mysql->run("truncate table `$table`");
        }
    }


    public function testCreateTeacher()
    {
        $tuid = \Teacher\dataUser::sole($this->platform)->append(3, 'teacher', 0);
        $this->assertNotFalse($tuid, "init tuid");
        $datum = new unitTeacherDatum;
        $res = \Teacher\servTeacher::sole($this->platform)->apply($tuid, $datum);
        $this->assertNotEmpty($res, "apply teacher");
        return $tuid;
    }

    public function testCreateStudent()
    {
        $suid = \Teacher\dataUser::sole($this->platform)->append(4, 'student', 0);
        $this->assertNotFalse($suid, "Failed to init suid");
    }

    /**
     * @depends testCreateTeacher
     * @param $tuid
     * @return bool|string
     */
    public function testCreateSeries($tuid)
    {
        $introduce = unitIntroduce::inst('test', 'series info', null);
        $scheme = unitLessonSeriesScheme::inst();
        $seriesSn = servLessonSeries::sole($this->platform)->create($tuid, 'series', $introduce, $scheme);
        $this->assertNotEmpty($seriesSn);
        return $seriesSn;
    }

}
