<?php namespace ZN\Crontab;

class LimitTest extends \PHPUnit\Framework\TestCase
{    
    public function testMake()
    {
        (new Job)->dayNumber(3)->command('ZN\Crontab\LimitTest:testLimit'); 
    }

    public function testLimit()
    {
        \Crontab::limit(2);
    }

    public function testLimitAgain()
    {
        $this->testLimit();
    }
}