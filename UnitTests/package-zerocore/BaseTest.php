<?php namespace ZN;

class BaseTest extends ZerocoreExtends
{
    public function testBuild()
    {
        $this->assertEquals('Frontend', Base::project());
    }   

    public function testIllustrate()
    {
        $this->assertEquals('ZN EXAMPLE CONST', Base::illustrate('ZN_EXAMPLE_CONST', 'ZN EXAMPLE CONST'));
        $this->assertEquals(PHP_VERSION, Base::illustrate('PHP_VERSION'));
        $this->assertEquals('VALUE', Base::illustrate('PHP_VERSION', 'VALUE'));
    }   

    public function testLayer()
    {
        $this->assertNull(Base::layer('Top'));
    } 

    public function testHost()
    {
        $_SERVER['HTTP_X_FORWARDED_HOST'] = 'localhost';

        $this->assertEquals('localhost', Base::host());

        Base::illustrate('IS_MAIN_DOMAIN', true);

        $this->assertEquals('www.localhost', Base::host());
    }

    public function testHeaders()
    {
        $this->assertNull(Base::headers('Cache-Control: no-cache'));
        $this->assertNull(Base::headers(['Cache-Control: no-cache']));
    }
}