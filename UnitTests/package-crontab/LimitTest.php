<?php namespace ZN\Crontab;

use Crontab;

class LimitTest extends \PHPUnit\Framework\TestCase
{    
    public function testMake()
    {
        $class = new class() extends \Project\Commands\Command
        {
            public function do()
            {
                Crontab::limit(2);
            }
        };

        $class->do();
    }
}