<?php namespace ZN\ErrorHandling;

class ErrorsTest extends \PHPUnit\Framework\TestCase
{
    public function testMessage()
    {
        $this->assertStringContainsString('table is not exists!', Errors::message('Database', 'tableNotExistsError' , ['%' => 'table']));
        $this->assertStringContainsString('table is not exists!', Errors::message('Database', 'tableNotExistsError' , 'table'));
        $this->assertStringContainsString('myMessage', Errors::message('myMessage'));
    }

    public function testLast()
    {
        $this->assertIsArray(Errors::last());
        $this->assertFalse(Errors::last('uknown'));
    }

    public function testLog()
    {
        $this->assertTrue(Errors::log('hello'));
    }

    public function testReport()
    {
        $this->assertIsInt(Errors::report());
        $this->assertIsInt(Errors::report(1));
    }

    public function testHandler()
    {
        $this->assertNull(Errors::handler());
    }

    public function testTrigger()
    {
        $this->assertIsBool(Errors::trigger('message'));
    }

    public function testRestore()
    {
        $this->assertNull(Errors::restore());
    }
}