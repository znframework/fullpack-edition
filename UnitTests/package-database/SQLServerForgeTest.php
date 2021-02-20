<?php namespace ZN\Database;

class SQLServerForgeTest extends DatabaseExtends
{ 
    public function testExtras()
    {
        $this->assertNull((new SQLServer\DBForge)->extras([]));
    }

    public function testRenameColumn()
    {
        $this->assertEquals("sp_rename 'table.oldcolumn', 'newcolumn', 'COLUMN';", (new SQLServer\DBForge)->renameColumn('table', ['oldcolumn' => 'newcolumn']));
    }

    public function testDropIndex()
    {
        $this->assertEquals('DROP INDEX table.index;', (new SQLServer\DBForge)->dropIndex('index', 'table'));
    }
}