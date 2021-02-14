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
}