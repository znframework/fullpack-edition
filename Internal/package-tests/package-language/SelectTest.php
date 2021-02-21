<?php namespace ZN\Language;

use ML;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testSelect()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);

        $this->assertIsString(ML::select('desk'));
    }

    public function testSelectAll()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $this->assertIsString(ML::selectAll('en')['desk']);
    }

    public function testSelectNULLParameter()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);

        $this->assertIsArray(ML::select(NULL));
    }

    public function testSelectChange()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);

        $this->assertIsString(ML::select('pencil', ['desk' => 'Desk2']));
    }

    public function testSelectAllArray()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $this->assertIsArray(ML::selectAll(['en', 'tr']));
    }

    public function testSelectAllReturnEmpty()
    {
        $this->assertEmpty(ML::selectAll(function(){}));
    }
}