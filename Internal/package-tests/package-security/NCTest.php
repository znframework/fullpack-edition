<?php namespace ZN\Security;

use Security;

class NCTest extends \PHPUnit\Framework\TestCase
{
    public function testNC()
    {
        $this->assertEquals('hi [x]', Security::ncEncode('hi bro', 'bro', '[x]'));
    }

    public function testNCSecondParameterWithArray()
    {
        $this->assertEquals('hi [x]', Security::ncEncode('hi bro', ['bro'], '[x]'));
    }

    public function testNCThirdParameterWithArray()
    {
        $this->assertEquals('hi [x] [y]', Security::ncEncode('hi bro mro', ['bro', 'mro'], ['[x]', '[y]']));
    }

    public function testNCSecondParameterWithNULL()
    {
        $this->assertEquals('hi bro', Security::ncEncode('hi bro', NULL, '[x]'));
    }
}