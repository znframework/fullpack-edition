<?php namespace ZN\Database;

class ConnectionTest extends DatabaseExtends
{
    public function testStringQueriesReturnFalse()
    {
        $this->assertFalse((new Connection)->stringQueries());
    }

    public function testClose()
    {
        (new Connection)->close();
    }

    public function testVersion()
    {
        $this->assertIsString((new Connection)->version());
    }
}