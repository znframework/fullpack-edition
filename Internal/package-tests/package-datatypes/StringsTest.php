<?php namespace ZN\DataTypes;

use Strings;

class StringsTest extends \PHPUnit\Framework\TestCase
{
    public function testToArray()
    {
        $this->assertEquals(['a', 'b', 'c'], Strings::toArray('a b c'));
    }

    public function testPad()
    {
        $this->assertEquals('a b c', Strings::pad('a b c', 1));
    }
}