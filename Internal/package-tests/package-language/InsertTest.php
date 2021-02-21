<?php namespace ZN\Language;

use ML;

class InsertTest extends \PHPUnit\Framework\TestCase
{
    public function testInsert()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $this->assertSame('Pencil', ML::select('pencil'));
        $this->assertSame('Desk', ML::select('desk'));
    }
}