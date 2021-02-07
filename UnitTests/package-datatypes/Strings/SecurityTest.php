<?php namespace ZN\DataTypes\Strings;

use Strings;

class SecurityTest extends \PHPUnit\Framework\TestCase
{
    public function testAddSlashes()
    {
        $this->assertSame('foo', Strings::addSlashes('foo', '+'));
    }

    public function testRemoveSlashes()
    {
        $this->assertSame('foo', Strings::removeSlashes('foo'));
    }
}