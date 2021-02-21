<?php namespace ZN\DataTypes\Arrays;

use Arrays;

class ExcludeIncludeTest extends \PHPUnit\Framework\TestCase
{
    public function testExclude()
    {
        $array = ['foo', 'bar', 'baz' => 'BAZ', 'zoo' => 'ZOO', 'doo'];

        $array = Arrays::exclude($array, ['bar', 'BAZ', 'zoo']);

        $this->assertIsArray($array);
    }

    public function testExcludeLogicException()
    {
        $array = ['foo'];

        $this->assertEmpty(Arrays::exclude($array, ['bar', 'BAZ', 'zoo']));
    }

    public function testInclude()
    {
        $array = ['foo', 'bar', 'baz' => 'BAZ', 'zoo' => 'ZOO', 'doo'];

        $array = Arrays::include($array, ['bar', 'BAZ', 'zoo']);

        $this->assertIsArray($array);
    }

    public function testIncludeLogicException()
    {
        $array = ['foo'];

        $this->assertEmpty(Arrays::include($array, ['bar', 'BAZ', 'zoo']));
    }
}