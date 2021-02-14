<?php namespace ZN\Database;

class OracleForgeTest extends DatabaseExtends
{ 
    public function testCreateTempTable()
    {
        $this->assertEquals
        (
            'CREATE GLOBAL TEMPORARY TABLE table(id int) extras; ON COMMIT PRESERVE ROWS;', 
            (new Oracle\DBForge)->createTempTable('table', ['id' => 'int'], 'extras')
        );
    }

    public function testRenameColumn()
    {
        $this->assertEquals
        (
            'ALTER TABLE table RENAME COLUMN 0 TO column;', 
            (new Oracle\DBForge)->renameColumn('table', ['column', 'recolumn'])
        );
    }

    public function testDropIndex()
    {
        $this->assertEquals
        (
            'DROP INDEX index;', 
            (new Oracle\DBForge)->dropIndex('index')
        );
    }
}