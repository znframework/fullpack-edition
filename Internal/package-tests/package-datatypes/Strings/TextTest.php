<?php namespace ZN\DataTypes\Strings;

use Strings;

class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testReshufle()
    {
        $this->assertSame('foo zoo bar', Strings::reshuffle('foo bar zoo', 'bar', 'zoo'));
    }

    public function testRecurrentCount()
    {
        $this->assertSame(2, Strings::recurrentCount('foo bar zoo bar', 'bar'));
    }

    public function testPlacement()
    {
        $this->assertSame('foo bar zoo baz', Strings::placement('foo ? zoo ?', '?', ['bar', 'baz']));
        $this->assertSame('foo', Strings::placement('foo', '', []));
        $this->assertSame('foo ? ?', Strings::placement('foo ? ?', '?', ['a']));
    }

    public function testSection()
    {
        $this->assertSame('bar', Strings::section('foo bar baz', 4, 3));
    }

    public function testReplace()
    {
        $this->assertSame('foo bar zoo', Strings::replace('foo bar baz', 'baz', 'zoo'));
        $this->assertSame('foo bar zoo', Strings::replace('foo bar baz', 'baz', 'zoo', false));
    }

    public function testToArray()
    {
        $this->assertSame(['foo', 'bar', 'baz'], Strings::toArray('foo bar baz', ' '));
    }
}