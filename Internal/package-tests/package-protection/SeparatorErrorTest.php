<?php namespace ZN\Protection;

use Separator;

class SeparatorErrorTest extends ProtectionExtends
{
    public function testError()
    {
        $this->assertEquals('', Separator::error());
    }

    public function testErrno()
    {
        $this->assertEquals(0, Separator::errno());
    }

    public function testCheck()
    {
        $this->assertTrue(Separator::check('data'));
    }
}