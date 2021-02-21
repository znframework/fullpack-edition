<?php namespace ZN\Response;

use Redirect;

class SelectDataTest extends \PHPUnit\Framework\TestCase
{
    public function testSelectData()
    {
        Redirect::location('profile', 0, ['example' => 'Data'], false);

        $this->assertEquals('Data', Redirect::select('example', true));
    }

    public function testSelectDataReturnFalse()
    {
        $this->assertFalse(Redirect::selectData('unknown'));
    }
}