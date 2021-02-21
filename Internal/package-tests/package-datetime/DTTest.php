<?php namespace ZN\DateTime;

use DT;

class DTTest extends \PHPUnit\Framework\TestCase
{
    public function testDate()
    {
        $this->assertSame('2018-03-06', DT::date('2018/01/01')->addDay(5)->addMonth(2)->get());
    }

    public function testTime()
    {
        $this->assertSame('11:45', DT::time('10:30')->addHour(1)->addMinute(15)->get('{hour}:{minute}'));
    }
}