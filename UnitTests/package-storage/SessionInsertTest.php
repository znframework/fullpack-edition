<?php namespace ZN\Storage;

use Session;

class SessionInsertTest extends \PHPUnit\Framework\TestCase
{
    public function testInsert()
    {
        $this->assertTrue(Session::insert('example', 'Example'));
    }

    public function testInsertCall()
    {
        $this->assertTrue(Session::example('Example'));
    }

    public function testInsertFirstMethod()
    {
        Session::delete('example');
        Session::insert('example', 'Example');
        Session::first()->insert('example', 'Example2');

        $this->assertEquals(['Example2', 'Example'], Session::example());
    }

    public function testInsertLastMethod()
    {
        Session::delete('example');
        Session::insert('example', 'Example');
        Session::last()->insert('example', 'Example2');

        $this->assertEquals(['Example', 'Example2'], Session::example());
    }
}