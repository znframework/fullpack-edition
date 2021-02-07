<?php namespace ZN\DataTypes\Strings;

use Strings;

class ElementTest extends \PHPUnit\Framework\TestCase
{
    public function testFirst()
    {
        $this->assertEquals('zoo', Strings::removeFirst('foo/bar/zoo', '/', 2));
        $this->assertEquals('bar', Strings::removeFirst('bar', '/', 1));
        $this->assertEquals('', Strings::removeFirst('foo/bar', '/', 3));
        $this->assertEquals('bar', Strings::removeFirst('foo/bar', '/', -1));
    }

    public function testLast()
    {
        $this->assertEquals('foo', Strings::removeLast('foo/bar', '/', 1));
        $this->assertEquals('foo', Strings::removeLast('foo/bar/zoo', '/', 2));
    }
}