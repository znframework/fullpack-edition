<?php namespace ZN\Crontab;

use Crontab;

class QueueTest extends \PHPUnit\Framework\TestCase
{    
    public function testMake()
    {
        $class = new class() extends \Project\Commands\Command
        {
            public function do()
            {
                Crontab::queue(function($queueIndex, $decrement)
                {   
                    if( $queueIndex === 2 )
                    {
                        return false;
                    }
                });
            }
        };

        $class->do();
    }
}