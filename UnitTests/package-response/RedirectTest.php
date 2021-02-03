<?php namespace ZN\Response;

use Redirect;

class RedirectTest extends \PHPUnit\Framework\TestCase
{
    public function testLocation()
    {
        Redirect::location('profile', 0, ['example' => 'Data'], false);
    }

    public function testSelect()
    {
        $this->assertEquals('Data', Redirect::select('example'));
    }

    public function testStatus()
    {
        $this->assertIsInt(Redirect::status());
    }

    public function testUrl()
    {
        $this->assertIsString(Redirect::url());
    }

    public function testQueryString()
    {
        $this->assertIsString(Redirect::queryString());
    }

    public function testCode()
    {
        $this->assertIsObject(Redirect::code(5));
    }

    public function testSelectData()
    {
        Redirect::location('profile', 0, ['example' => 'Data'], false);

        $this->assertEquals('Data', Redirect::select('example', true));
    }

    public function testSelectDataReturnFalse()
    {
        $this->assertFalse(Redirect::select('unknown'));
    }
}