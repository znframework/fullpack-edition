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
        try
        {
            $array = ['foo'];

            $array = Arrays::exclude($array, ['bar', 'BAZ', 'zoo']);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testInclude()
    {
        $array = ['foo', 'bar', 'baz' => 'BAZ', 'zoo' => 'ZOO', 'doo'];

        $array = Arrays::include($array, ['bar', 'BAZ', 'zoo']);

        $this->assertIsArray($array);
    }

    public function testIncludeLogicException()
    {
        try
        {
            $array = ['foo'];

            $array = Arrays::include($array, ['bar', 'BAZ', 'zoo']);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}