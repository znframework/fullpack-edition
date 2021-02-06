<?php namespace ZN\Database;

class PostgresForgeTest extends DatabaseExtends
{ 
    public function testExtras()
    {
        $this->assertNull((new Postgres\DBForge)->extras([]));
    }

    public function testModifyColumn()
    {
        $this->assertEquals('ALTER TABLE test ALTER COLUMN id TYPE int;', (new Postgres\DBForge)->modifyColumn('test', ['id' => 'int']));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE test RENAME COLUMN id TO int;', (new Postgres\DBForge)->renameColumn('test', ['id' => 'int']));
    }

    public function testAddColumn()
    {
        $this->assertEquals('ALTER TABLE test ADD id int;', (new Postgres\DBForge)->addColumn('test', ['id' => 'int']));
    }
}