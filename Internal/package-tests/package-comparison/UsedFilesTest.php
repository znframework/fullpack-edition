<?php namespace ZN\Comparison;

use Benchmark;

class UsedFilesTest extends \PHPUnit\Framework\TestCase
{
    public function testGetUsedFiles()
    {
        Benchmark::start('test');
        
        \Encode::super('test');

        Benchmark::end('test');

        $this->assertIsArray(Benchmark::usedFiles('test'));
    }

    public function testGetUsedFilesAll()
    {
        $this->assertIsArray(Benchmark::usedFiles());
    }

    public function testGetUsedFilesStartException()
    {
        try
        {
            Benchmark::end('testGetUsedFilesStartException');

            Benchmark::usedFiles('testGetUsedFilesStartException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGetUsedFilesEndException()
    {
        try
        {
            Benchmark::start('testGetUsedFilesEndException');

            Benchmark::usedFiles('testGetUsedFilesEndException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGetUsedFileCount()
    {
        Benchmark::start('test');
        
        \Encode::super('test');

        Benchmark::end('test');

        $this->assertIsInt(Benchmark::usedFileCount('test'));
    }

    public function testGetUsedFileCountAll()
    {
        $this->assertIsInt(Benchmark::usedFileCount());
    }

    public function testGetUsedFileCountStartException()
    {
        try
        {
            Benchmark::end('testGetUsedFileCountStartException');

            Benchmark::usedFileCount('testGetUsedFileCountStartException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGetUsedFileCountEndException()
    {
        try
        {
            Benchmark::start('testGetUsedFileCountEndException');

            Benchmark::usedFileCount('testGetUsedFileCountEndException');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}