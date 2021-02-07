<?php namespace ZN\DataTypes;

use Arrays;

class ArraysTest extends \PHPUnit\Framework\TestCase
{
    public function testValueExists()
    {
        $this->assertTrue(Arrays::valueExists(['a'], 'a'));
    }

    public function testValueExistsInsensitive()
    {
        $this->assertTrue(Arrays::valueExistsInsensitive(['a'], 'A'));
    }

    public function testKeyExists()
    {
        $this->assertTrue(Arrays::keyExists(['a' => 'A'], 'a'));
    }

    public function testKeyExistsInsensitive()
    {
        $this->assertTrue(Arrays::keyEsistsInsensitive(['a' => 'A'], 'A'));
    }

    public function testSearch()
    {
        $this->assertEquals(1, Arrays::search(['a', 'b'], 'b'));
    }

    public function testCountSameValues()
    {
        $this->assertEquals(3, Arrays::countSameValues(['a', 'b', 'b', 'b'], 'b'));
        $this->assertEquals(['a' => 1, 'b' => 3], Arrays::countSameValues(['a', 'b', 'b', 'b']));
    }
}