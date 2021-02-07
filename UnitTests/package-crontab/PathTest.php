<?php namespace ZN\Crontab;

class PathTest extends \PHPUnit\Framework\TestCase
{    
    public function testRunCronSetPHPPath()
    {
        (new Job)->path('/opt/lampp/bin/php')->daily()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('/opt/lampp/bin/php', (new Job)->list());

        (new Job)->remove('/opt/lampp/bin/php');
    }
}