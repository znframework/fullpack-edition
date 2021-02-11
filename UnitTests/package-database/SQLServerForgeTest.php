<?php namespace ZN\Database;

class SQLServerForgeTest extends DatabaseExtends
{ 
    public function testExtras()
    {
        $this->assertNull((new SQLServer\DBForge)->extras([]));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE table RENAME COLUMN oldcolumn TO newcolumn;', (new SQLServer\DBForge)->renameColumn('table', ['oldcolumn' => 'newcolumn']));
    }

    public function testDropIndex()
    {
        $this->assertEquals('DROP INDEX index.table;', (new SQLServer\DBForge)->dropIndex('index', 'table'));
    }
}