<?php namespace ZN\Language;

use ML;

class UpdateTest extends \PHPUnit\Framework\TestCase
{
    public function testUpdate()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        ML::lang('en');
        
        ML::update('en', 'order', 'Orderx');

        $this->assertSame('Orderx', ML::select('order'));
    }
}