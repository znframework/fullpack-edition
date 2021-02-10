<?php namespace ZN\Database;

use DB;

class SimpleTest extends DatabaseExtends
{
    public function testSimpleResult()
    {
        DB::switchCase('name', []);
        DB::simpleResult('persons', 'id', 1);

        $this->assertEquals("SELECT  CASE name END  FROM persons  WHERE id =  '1' ", DB::stringQuery());
    }

    public function testSimpleResultArray()
    {
        DB::simpleResultArray('persons', 'id', 1);

        $this->assertEquals("SELECT  *  FROM persons  WHERE id =  '1' ", DB::stringQuery());
    }

    public function testSimpleTotalRows()
    {
        DB::simpleTotalRows('persons');

        $this->assertEquals("SELECT  *  FROM persons ", DB::stringQuery());
    }

    public function testSimpleTotalColumns()
    {
        DB::simpleTotalColumns('persons');

        $this->assertEquals("SELECT  *  FROM persons ", DB::stringQuery());
    }

    public function testSimpleColumns()
    {
        DB::simpleColumns('persons');

        $this->assertEquals("SELECT  *  FROM persons ", DB::stringQuery());
    }

    public function testSimpleColumnData()
    {
        DB::simpleColumnData('persons', 'name');

        $this->assertEquals("SELECT  *  FROM persons ", DB::stringQuery());
    }

    public function testSimpleUpdate()
    {
        DB::simpleUpdate('table', ['col' => 'val'], 'column', 'value');

        $this->assertEquals("UPDATE table SET col='val' WHERE column =  'value' ", DB::stringQuery());
    }

    public function testSimpleDelete()
    {
        DB::simpleDelete('table', 'column', 'value');

        $this->assertEquals("DELETE  FROM table WHERE column =  'value' ", DB::stringQuery());
    }
    
}