<?php
namespace Core\Library;

class mysqlTest extends \PHPUnit_Framework_TestCase
{
    private static $seed;

    private $mysql;

    public function __construct()
    {
        parent::__construct();
        self::$seed = base_convert(time(), 10, 32);
        $this->mysql = Mysql::inst('test');
    }

    public static function setupBeforeClass()
    {
        $seed = self::$seed;
        $mysql = Mysql::inst('test');
        $sqlCreate = "
            CREATE TABLE `test_$seed` (
              `id` int not null auto_increment,
              `a` char(2) not null,
              `b` char(2) not null,
              primary key (id)
            )
        ";
        $mysql->run($sqlCreate);
    }

    public static function teardownAfterClass()
    {
        $seed = self::$seed;
        $mysql = Mysql::inst('test');
        $mysql->run("DROP TABLE `test_$seed`");
    }

    public function setup()
    {
        /*
            +----+----+----+
            | id | a  | b  |
            +----+----+----+
            |  1 | 1A | 1B |
            |  2 | 2A | 2B |
            +----+----+----+
        */
        $seed = self::$seed;
        $mysql = Mysql::inst('test');
        $mysql->run("TRUNCATE TABLE `test_$seed`");
        $sqlInsert = "INSERT INTO `test_$seed` (a, b) VALUES ('1A','1B'), ('2A','2B')";
        $mysql->run($sqlInsert);
    }

    public function testRun()
    {
        $res = $this->mysql->run("select 1")->fetch();
        $this->assertNotEmpty($res, "Base run.");
    }

    public function testChain()
    {
        $res = $this->mysql->s("select 1")->e()->fetch();
        $this->assertNotEmpty($res, "Base chain.");
    }

    public function testFetch()
    {
        /*
            +---+---+
            | a | b |
            +---+---+
            | 1 | 2 |
            +---+---+
        */
        $sql = "select 1 as a, 2 as b";
        $res = $this->mysql->run($sql)->fetch();
        $this->assertEquals($res, ['a' => 1, 'b' => 2], "Fetch without speciefied field.");

        $res = $this->mysql->run($sql)->fetch('a');
        $this->assertEquals($res, 1, "Fetch by field name");

        $res = $this->mysql->run($sql)->fetch(1);
        $this->assertEquals($res, 2, "Fetch by field position.");
    }

    public function testFetchAll()
    {
        /*
            +----+----+
            | a  | b  |
            +----+----+
            | 1A | 1B |
            | 2A | 2B |
            +----+----+
         */
        $sql = "select '1A' as a, '1B' as b union select '2A', '2B'";

        $res = $this->mysql->run($sql)->fetchAll();
        $exp = [
            ['a' => '1A', 'b' => '1B'],
            ['a' => '2A', 'b' => '2B'],
        ];
        $this->assertEquals($exp, $res, "FetchAll without speciefied key and value.");

        $res = $this->mysql->run($sql)->fetchAll('a');
        $exp = [
            '1A' => ['a' => '1A', 'b' => '1B'],
            '2A' => ['a' => '2A', 'b' => '2B'],
        ];
        $this->assertEquals($exp, $res, "FetchAll with a primary key field, which speciefied by name.");


        $res = $this->mysql->run($sql)->fetchAll(null, 'b');
        $exp = ['1B', '2B'];
        $this->assertEquals($exp, $res, "FetchAll as a column by field name.");

        $res = $this->mysql->run($sql)->fetchAll('a', 'b');
        $exp = [
            '1A' => '1B',
            '2A' => '2B',
        ];
        $this->assertEquals($exp, $res, "FetchAll as a key-value map, by field name.");

        $res = $this->mysql->run($sql)->fetchAll(1, 0);
        $exp = [
            '1B' => '1A',
            '2B' => '2A',
        ];
        $this->assertEquals($exp, $res, "FetchAll as a key-value map, by field position.");
    }

    public function testW()
    {
        $table = 'test_' . self::$seed;

        $conds = ['id' => 1];
        $res = $this->mysql->s("select * from $table")->w($conds)->e()->fetch();
        $exp = ['id' => '1', 'a' => '1A', 'b' => '1B'];
        $this->assertEquals($exp, $res, "Where id=1, set by key-value map.");

        $conds = ["id=2"];
        $res = $this->mysql->s("select * from $table")->w($conds)->e()->fetch();
        $exp = ['id' => '2', 'a' => '2A', 'b' => '2B'];
        $this->assertEquals($exp, $res, "Where id=2, set by num-index array.");

        $conds = ['a=?' => ['1A']];
        $res = $this->mysql->s("select * from $table")->w($conds)->e()->fetch();
        $exp = ['id' => '1', 'a' => '1A', 'b' => '1B'];
        $this->assertEquals($exp, $res, "Where a='1A', set by placeholder.");

        $res = $this->mysql->s("select id from $table")->w("a='1A'", "b='2b'")->e()->fetchAll(null, 'id');
        $exp = ['1', '2'];
        $this->assertEquals($exp, $res, "Where a='1A' or b='2B', set by multi-condition");
    }

    public function testSelect()
    {
        $table = 'test_' . self::$seed;

        //SELECT count(*) FROM $table
        $res = $this->mysql->select($table, 'count(*)')->fetch(0);
        $exp = 2;
        $this->assertEquals($exp, $res, "Select count");

        $res = $this->mysql->select($table, ['id', 'a'])->fetchAll('id', 'a');
        $exp = [
            '1' => '1A',
            '2' => '2A',
        ];
        $this->assertEquals($exp, $res, "Select map by fields list");

        //SELECT * FROM $table WHERE ID=?;[2]
        $res = $this->mysql->select($table, '*', ['id' => 2])->fetch(2);
        $exp = '2B';
        $this->assertEquals($exp, $res, "Select with where conditions");

        //SELECT * FROM $table order by id desc limit 1
        $res = $this->mysql->select($table, '*', null, "order by id desc limit 1")->fetch('b');
        $exp = '2B';
        $this->assertEquals($exp, $res, "Select with extra conditions.");
    }

    public function testInsert()
    {
        $table = 'test_' . self::$seed;

        $data = [
            'a' => '3A',
            'b' => '3B',
        ];

        //INSERT INTO $table `a`,`b` VALUES (?,?); ['3A','3B']
        $rowCount = $this->mysql->insert($table, $data)->rowCount();
        $this->assertEquals(1, $rowCount, "Insert row count.");
        $lastInsertId = $this->mysql->lastInsertId();
        $this->assertEquals(3, $lastInsertId, "Insert's last id.");
        $id = $this->mysql->select($table, "max(id)")->fetch(0);
        $this->assertEquals(3, $id, "Insert one row and get id.");

        $data = [
            'id' => '1',
            'a' => '1A',
        ];

        //INSERT INTO $table `id`,`a` VALUES (?,?) ON DUPLICATE `a`=VALUES(`a`);['1','1A']
        $this->mysql->insert($table, $data, ['a']);
        $res = $this->mysql->select($table, 'a', ['id'=>1])->fetch(0);
        $this->assertEquals($data['a'], $res, "Insert on duplicate key.");

    }

    public function testInsertBatch()
    {
        $table = 'test_' . self::$seed;

        $fields = ['a', 'b'];
        $values = [
            ['3A', '3B'],
            ['4A', '4B'],
        ];
        //INSERT INTO $table `a`,`b` VALUES (?,?),(?,?); ['3A','3B','4A','4B']
        $this->mysql->insertBatch($table, $fields, $values);
        $res = $this->mysql->select($table, $fields, null, "order by id desc limit 2")->fetchAll();
        $exp = [
            ['a' => '4A', 'b' => '4B'],
            ['a' => '3A', 'b' => '3B'],
        ];
        $this->assertEquals($exp, $res, "InsertBatch few rows");

        $max = 10000;
        $values = array_fill(0, $max, ['_A', '_B']);
        $this->mysql->insertBatch($table, $fields, $values, null, 2000);
        $res = $this->mysql->select($table, "count(*)")->fetch(0);
        $this->assertGreaterThan($max, $res, "InsertBatch $max rows.");
    }

    public function testUpdate()
    {
        $table = 'test_' . self::$seed;

        $data = [
            'a' => '_A',
            'b' => '_B',
        ];
        $cond = ['id'=>1];
        //UPDATE $table SET `a`=?,`b`=? WHERE `id`=?; ['_A','_B','1']
        $rowCount = $this->mysql->update($table, $data, $cond)->rowCount();
        $this->assertEquals(1, $rowCount, "Update row count.");
        $res = $this->mysql->select($table, array_keys($data), $cond)->fetch();
        $this->assertEquals($data, $res, "Update row data.");
    }

    public function testDelete()
    {
        $table = 'test_' . self::$seed;

        $cond = ['id'=>1];

        //DELETE FROM $table WHERE `id`=?; [1]
        $rowCount = $this->mysql->delete($table, $cond)->rowCount();
        $this->assertEquals(1, $rowCount, "Delete row count.");
        $res = $this->mysql->select($table, '*', $cond)->fetch();
        $this->assertEmpty($res, "Delete row.");
    }

    public function testDropTables()
    {
        $table = 'test_' . self::$seed;

        $this->mysql->run("CREATE TABLE IF NOT EXISTS `test_drop_1` like $table");
        $this->mysql->run("CREATE TABLE IF NOT EXISTS `test_drop_2` like $table");
        $tableCount = $this->mysql->run("SHOW TABLES LIKE 'test_drop_%'")->rowCount();
        $this->assertEquals(2, $tableCount, "Prepare 2 tables to drop.");

        $this->mysql->s("DROP TABLES")->k(['test_drop_1', 'test_drop_2'])->e();
        $tableCount = $this->mysql->run("SHOW TABLES LIKE 'test_drop_%'")->rowCount();
        $this->assertEquals(0, $tableCount, "Drop 2 tables create before.");

    }

    public function testMakeFields()
    {
        $makeFields = new \ReflectionMethod($this->mysql, 'makeFields');
        $makeFields->setAccessible(true);

        $res = $makeFields->invoke($this->mysql, ['a', 'b']);
        $exp = "`a`,`b`";
        $this->assertEquals($exp, $res, "MakeFields by list.");

        $res = $makeFields->invoke($this->mysql, '*');
        $exp = '*';
        $this->assertEquals($exp, $res, "MakeFields by string.");
    }

    public function testMakeWheres()
    {
        $makeWheres = new \ReflectionMethod($this->mysql, 'makeWheres');
        $makeWheres->setAccessible(true);

        $wheres = 'id=1';
        $res = $makeWheres->invoke($this->mysql, $wheres);
        $exp = [
            'clause' => "(id=1)",
            'params' => [],
        ];
        $this->assertEquals($res, $exp, "MakeConditiion by string.");

        $wheres = ['a' => 'A'];
        $res = $makeWheres->invoke($this->mysql, $wheres);
        $exp = [
            'clause' => "(`a`=?)",
            'params' => ['A'],
        ];
        $this->assertEquals($res, $exp, "MakeWheres by key-value.");

        $wheres = ['id=1'];
        $res = $makeWheres->invoke($this->mysql, $wheres);
        $exp = [
            'clause' => "(id=1)",
            'params' => [],
        ];
        $this->assertEquals($res, $exp, "MakeWheres by num-index.");

        $wheres = [
            "id>1",
            'a' => 'A',
            'b between ? and ?' => [1, 2]
        ];
        $res = $makeWheres->invoke($this->mysql, $wheres);
        $exp = [
            'clause' => "(id>1) AND (`a`=?) AND (b between ? and ?)",
            'params' => ['A', 1, 2],
        ];
        $this->assertEquals($res, $exp, "MakeWheres by hybird-array.");

        $res = $makeWheres->invoke($this->mysql, $wheres, ['b' => 'B']);
        $exp = [
            'clause' => "(id>1) AND (`a`=?) AND (b between ? and ?) OR (`b`=?)",
            'params' => ['A', 1, 2, 'B'],
        ];
        $this->assertEquals($res, $exp, "MakeWheres by muti-array.");
    }

    public function testMakeData()
    {
        $makeData = new \ReflectionMethod($this->mysql, 'makeData');
        $makeData->setAccessible(true);

        $data = ['a', 'b'];
        $res = $makeData->invoke($this->mysql, $data, '?', ',');
        $this->assertEquals("?,?", $res['clause'], "MakeData for list clause.");
        $this->assertEquals($data, $res['params'], "MakeData for list values.");

        $data = [
            'a'=>'A',
            'b' => 'B',
        ];
        $res = $makeData->invoke($this->mysql, $data, '%s=?', ',');
        $this->assertEquals("a=?,b=?", $res['clause'], "MakeData for key-value map: clause.");
        $this->assertEquals(['A','B'], $res['params'], "MakeData for key-value map: values.");
    }

}
