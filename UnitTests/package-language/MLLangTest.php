<?php namespace ZN\Language;

use ML;

class MLLangTest extends \PHPUnit\Framework\TestCase
{
    public function testLang()
    {
        ML::insert('en', 'pencil', 'Pencil');
        ML::insert('tr', 'pencil', 'Kalem');
        
        $this->assertSame('Pencil', ML::select('pencil'));

        ML::lang('tr');

        $this->assertSame('Kalem', ML::select('pencil'));
    }
}