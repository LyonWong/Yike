<?php


namespace _;


class dataUserKeepTest extends \PHPUnit_Framework_TestCase
{
    private $platform;
    private $dao;

    public function __construct(string $name = null, array $data = array(), string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dao = dataUserKeep::sole($this->platform);
    }

    public static function tearDownAfterClass()
    {
        $mysql = data::mysql();
        $tables = $mysql->run("show tables")->fetchAll(null, 0);
        foreach ($tables as $table) {
            $mysql->run("truncate table `$table`");
        }
    }

    public function testSetAttr()
    {
        $this->dao->varAttr(1, dataUserKeep::ITEM_MONEY, '$.expect', 10);
        $obj = $this->dao->obj(1,dataUserKeep::ITEM_MONEY);
        $this->assertEquals(10, $obj['expect']);
    }


}
