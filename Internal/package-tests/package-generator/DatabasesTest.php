<?php namespace ZN\Generator;

use Generate;

class DatabasesTest extends GeneratorExtends
{
    public function testDatabases()
    {
        Generate::databases();
    }

    public function testCallException()
    {
        try
        {
            Generate::abc();
        }
        catch( Exception\InvalidTypeException $e )
        {
            $this->assertIsString($e->getMessage());
        }
        
    }
}