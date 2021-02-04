<?php namespace ZN\Language;

use ML;

class LangsTest extends \PHPUnit\Framework\TestCase
{
    public function testLangs()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $this->assertIsArray(ML::langs());
    }
}