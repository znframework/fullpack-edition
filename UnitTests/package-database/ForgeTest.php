<?php namespace ZN\Database;

class ForgeTest extends DatabaseExtends
{
    public function testExtras()
    {
        $this->assertEquals('extras', (new \ZN\Database\DriverForge)->extras('extras'));
    }

    public function testCreateDatabase()
    {
        $this->assertEquals('CREATE DATABASE database extras1 extras2;', (new \ZN\Database\DriverForge)->createDatabase('database', ['extras1', 'extras2']));
    }
    
    public function testCreateTempTable()
    {
        $this->assertEquals('CREATE TEMPORARY TABLE table(id int) extras;', (new \ZN\Database\DriverForge)->createTempTable('table', ['id' => 'int'], 'extras'));
    }

    public function testTruncate()
    {
        $this->assertEquals('TRUNCATE TABLE table', (new \ZN\Database\DriverForge)->truncate('table'));
    }

    public function testAddColumn()
    {
        $this->assertEquals('ALTER TABLE column ADD (id int);', (new \ZN\Database\DriverForge)->addColumn('column', ['id' => 'int']));
    }

    public function testDropColumn()
    {
        $this->assertEquals('ALTER TABLE column DROP id;', (new \ZN\Database\DriverForge)->dropColumn('column', 'id'));
    }

    public function testStartAutoIncrement()
    {
        $this->assertEquals('ALTER TABLE table  AUTOINCREMENT =1;', (new \ZN\Database\DriverForge)->startAutoIncrement('table', 1));
    }

    public function testCreateUniqueIndex()
    {
        $this->assertEquals('CREATE UNIQUE INDEX index ON table (id);', (new \ZN\Database\DriverForge)->createUniqueIndex('index', 'table', 'id'));
    }

    public function testCreateFulltextIndex()
    {
        $this->assertEquals('CREATE FULLTEXT INDEX index ON table (id);', (new \ZN\Database\DriverForge)->createFulltextIndex('index', 'table', 'id'));
    }

    public function testModifyColumn()
    {
        $this->assertEquals('ALTER TABLE table MODIFY id int;', (new \ZN\Database\DriverForge)->modifyColumn('table', ['id' => 'int']));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE table CHANGE COLUMN id nid int;', (new \ZN\Database\DriverForge)->renameColumn('table', ['id' => 'nid int']));
    }

    public function testDBForge()
    {
        $forge = new DBForge;

        try
        {
            $forge->undefined(1);
        }
        catch( \Exception $e )
        {
            $this->assertEquals('Error: Call to undefined function `DBForge::undefined()`!', $e->getMessage());
        }
        
        $forge->extras('extras');
        $forge->createTempTable('temptable', ['id' => 'int']);
        $forge->alterTable('temptable', ['createTable' => ['int']]);
        $forge->startAutoIncrement('example');
        $forge->addAutoIncrement('example');
        $forge->addAutoIncrement('example', 'column', 2);
        $forge->createUniqueIndex('name', 'example', 'id');
        $forge->createFulltextIndex('name', 'example', 'id');
        $forge->dropColumn('example', 'id');
        $forge->dropColumn('example', ['id' => 'int']);
    }
}