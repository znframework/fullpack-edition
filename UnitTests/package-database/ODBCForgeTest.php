<?php namespace ZN\Database;

class ODBCForgeTest extends DatabaseExtends
{ 
    public function testTruncate()
    {
        $this->assertEquals('DELETE FROM table;', (new ODBC\DBForge)->truncate('table'));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE table RENAME COLUMN  column;', (new ODBC\DBForge)->renameColumn('table', 'column'));
    }

    public function testDropIndex()
    {
        $this->assertEquals('DROP INDEX index ON table;', (new ODBC\DBForge)->dropIndex('index', 'table'));
    }
}