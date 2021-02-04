<?php namespace ZN\Language;

use ML;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testSelect()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);

        $this->assertSame('Desk', ML::select('desk'));
    }

    public function testSelectAll()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $this->assertSame('Desk', ML::selectAll('en')['desk']);
    }
}