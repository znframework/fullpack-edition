<?php namespace ZN;

use Import;
use Buffer;

class ZNTest extends ZerocoreExtends
{
    public function testKernelRun()
    {
        $output = Buffer::callback(function()
        {
            Kernel::run();
        });
        
        $this->assertStringContainsString('<html>', $output);
    }

    public function testInSecretProjectKey()
    {
        $this->assertIsString(In::secretProjectKey());
    }

    public function testInCleanURIPrefix()
    {
        $this->assertEquals('project', In::cleanURIPrefix('tr/project', 'tr'));
    }

    public function testInBenchmarkReport()
    {
        Config::project('benchmark', true);

        define('START_BENCHMARK', 1); define('FINISH_BENCHMARK', 10);
        
        $output = Buffer::callback(function()
        {
            In::benchmarkReport();
        });

        $this->assertStringContainsString('BENCHMARK', $output);
    }
}