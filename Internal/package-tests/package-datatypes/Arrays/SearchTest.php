<?php namespace ZN\DataTypes\Arrays;

use Arrays;

class SearchTest extends \PHPUnit\Framework\TestCase
{
    const array = ['foo', 'bar', 'baz' => 'baz'];

    public function testBeetween()
    {
        $this->assertEquals([1 => 'bar'], Arrays::searchBetween(self::array, 'foo', 'baz'));
        $this->assertFalse(Arrays::searchBetween(self::array, 'foox', 'baz'));
    }

    public function testBeetweenBoth()
    {
        $this->assertEquals(['foo', 'bar', 'baz' => 'baz'], Arrays::searchBetweenBoth(self::array, 'foo', 'baz'));
        $this->assertFalse(Arrays::searchBetweenBoth(self::array, 'foox', 'baz'));
    }

    public function testKeyBeetween()
    {
        $this->assertEquals([1 => 'bar'], Arrays::searchKeyBetween(self::array, 0, 'baz'));
        $this->assertFalse(Arrays::searchKeyBetween(self::array, 4, 'bazx'));
    }

    public function testKeyBeetweenBoth()
    {
        $this->assertEquals(self::array, Arrays::searchKeyBetweenBoth(self::array, 0, 'baz'));
        $this->assertFalse(Arrays::searchKeyBetweenBoth(self::array, 4, 'bazx'));
    }

    public function testKeyToValueBeetween()
    {
        $this->assertEquals([1 => 'bar'], Arrays::searchKeyToValueBetween(self::array, 0, 'baz'));
        $this->assertFalse(Arrays::searchKeyToValueBetween(self::array, 4, 'bazx'));
    }

    public function testKeyToValueBeetweenBoth()
    {
        $this->assertEquals(self::array, Arrays::searchKeyToValueBetweenBoth(self::array, 0, 'baz'));
        $this->assertFalse(Arrays::searchKeyToValueBetweenBoth(self::array, 4, 'bazx'));
    }

    public function testValueToKeyBeetween()
    {
        $this->assertEquals([1 => 'bar'], Arrays::searchValueToKeyBetween(self::array, 'foo', 'baz'));
        $this->assertFalse(Arrays::searchValueToKeyBetween(self::array, 'foox', 'bazx'));
    }

    public function testValueToKeyBeetweenBoth()
    {
        $this->assertEquals(self::array, Arrays::searchValueToKeyBetweenBoth(self::array, 'foo', 'baz'));
        $this->assertFalse(Arrays::searchValueToKeyBetweenBoth(self::array, 'foox', 'bazx'));
    }
}