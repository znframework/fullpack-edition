<?php namespace ZN\Language;

use ML;

class KeysTest extends \PHPUnit\Framework\TestCase
{
    public function testKeys()
    {
        ML::insert('en', ['pencil' => 'Pencil', 'desk' => 'Desk', 'order' => 'Order']);
        
        $keys = ML::keys();

        $this->assertSame('Pencil', $keys->pencil);
    }
}