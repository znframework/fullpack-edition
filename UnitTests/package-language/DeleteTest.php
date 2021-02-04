<?php namespace ZN\Language;

use ML;

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    public function testDelete()
    {
        ML::delete('en', 'order');

        # If not, it returns itself.
        $this->assertSame('order', ML::select('order'));
    }

    public function testDeleteArray()
    {
        ML::delete('en', ['order']);

        # If not, it returns itself.
        $this->assertSame('order', ML::select('order'));
    }

    public function testDeleteAll()
    {
        ML::deleteAll();

        # If not, it returns itself.
        $this->assertSame('pencil', ML::select('pencil'));
    }

    public function testDeleteAllArray()
    {
        ML::deleteAll(['en', 'tr']);

        # If not, it returns itself.
        $this->assertSame('pencil', ML::select('pencil'));
    }
}