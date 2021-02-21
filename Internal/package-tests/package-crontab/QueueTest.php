<?php namespace ZN\Crontab;

class QueueTest extends \PHPUnit\Framework\TestCase
{    
    public function testMake()
    {
        (new Job)->dayNumber(3)->command('ZN\Crontab\QueueTest:testQueue'); 
    }

    public function testQueue()
    {
        \Crontab::queue(function($queueIndex, $decrement)
        {   
            if( $queueIndex === 1 )
            {
                return false;
            }
        });
    }

    public function testQueueAgain()
    {
        $this->testQueue();
    }
}