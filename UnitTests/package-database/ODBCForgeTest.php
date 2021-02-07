<?php namespace ZN\Database;

class ODBCForgeTest extends DatabaseExtends
{ 
    public function testTruncate()
    {
        $this->assertEquals('DELETE FROM test', (new ODBC\DBForge)->truncate('test'));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE test RENAME COLUMN  id;', (new ODBC\DBForge)->renameColumn('test', 'id'));
    }

    public function testDropIndex()
    {
        $this->assertEquals('DROP INDEX id ON test;', (new ODBC\DBForge)->dropIndex('id', 'test'));
    }
}