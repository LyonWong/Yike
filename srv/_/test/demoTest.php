<?php


namespace _;


class demoTest extends \PHPUnit\Framework\TestCase
{
    public function testFoo()
    {
        $this->assertSame('foo', 'foo');
        $res = data::mysql()->run("select database()")->fetch(0);
        $this->assertSame('yike_test', $res);
    }

}
