<?php namespace ZN\Crontab;

class WgetTest extends \PHPUnit\Framework\TestCase
{    
    public function testRunWget()
    {
        (new Job)->daily()->wget('https://site.com/example/page');

        $this->assertStringContainsString('https://site.com/example/page', (new Job)->list());

        (new Job)->remove('https://site.com/example/page');
    }
}