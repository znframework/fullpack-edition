<?php namespace ZN\Comparison;

use Benchmark;

class ElapsedTimeTest extends \PHPUnit\Framework\TestCase
{
    public function testGetElapsedTime()
    {
        Benchmark::start('test1');
        $a = 10;
        Benchmark::end('test1');

        $this->assertIsFloat(Benchmark::elapsedTime('test1'));
    }

    public function testGetElapsedTimeStartException()
    {
        try
        {
            Benchmark::end('testGetElapsedTimeStartException');

            Benchmark::elapsedTime('testGetElapsedTimeStartException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGetElapsedTimeEndException()
    {
        try
        {
            Benchmark::start('testGetElapsedTimeEndException');

            Benchmark::elapsedTime('testGetElapsedTimeEndException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}