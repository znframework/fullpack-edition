<?php namespace ZN\Crontab;

class ListTest extends \PHPUnit\Framework\TestCase
{    
    public function testCronList()
    {
        $cronList = (new Job)->list();

        $this->assertIsString($cronList);
    }

    public function testCronListArray()
    {
        $cronList = (new Job)->listArray();

        $this->assertIsArray($cronList);
    }
}