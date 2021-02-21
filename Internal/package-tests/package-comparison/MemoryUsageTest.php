<?php namespace ZN\Comparison;

use Benchmark;

class MemoryUsageTest extends \PHPUnit\Framework\TestCase
{
    public function testGetMemoryUsage()
    {
        Benchmark::start('test1');
        $a = 10;
        Benchmark::end('test1');

        $this->assertIsInt(Benchmark::memoryUsage('test1'));
    }

    public function testGetMaxMemoryUsage()
    {
        Benchmark::start('test1');
        $a = 10;
        Benchmark::end('test1');

        $this->assertIsInt(Benchmark::maxMemoryUsage('test1'));
    }

    public function testGetMaxMemoryUsageStartException()
    {
        try
        {
            Benchmark::end('testGetMaxMemoryUsageStartException');

            Benchmark::maxMemoryUsage('testGetMaxMemoryUsageStartException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGetMaxMemoryUsageEndException()
    {
        try
        {
            Benchmark::start('testGetMaxMemoryUsageEndException');

            Benchmark::maxMemoryUsage('testGetMaxMemoryUsageEndException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}