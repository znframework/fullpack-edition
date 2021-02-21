<?php namespace ZN\Database;

use DBTool;

class ToolTest extends DatabaseExtends
{
    public function testListDatabases()
    {
        $result = DBTool::listDatabases();

        $this->assertIsArray($result);

        $this->assertIsArray((new \ZN\Database\DriverTool)->listDatabases());
    }

    public function testListTables()
    {
        $result = DBTool::listTables();
        
        $this->assertIsArray($result);

        $this->assertIsArray((new \ZN\Database\DriverTool)->listTables());
    }

    public function testStatusTables()
    {
        $result = DBTool::statusTables();

        $this->assertFalse($result);

        $this->assertIsObject((new \ZN\Database\DriverTool)->statusTables('*'));
        $this->assertIsObject((new \ZN\Database\DriverTool)->statusTables(['persons']));
        $this->assertFalse((new \ZN\Database\DriverTool)->statusTables('persons'));
    }

    public function testOptimizeTables()
    {
        $result = DBTool::optimizeTables();

        $this->assertFalse($result);

        $this->assertEquals('The optimization process was completed successfully.', (new \ZN\Database\DriverTool)->optimizeTables('persons'));

        (new \ZN\Database\DriverTool)->optimizeTables('*');
        (new \ZN\Database\DriverTool)->optimizeTables(['persons']);
    }

    public function testRepairTables()
    {
        $result = DBTool::repairTables();

        $this->assertFalse($result);
    }

    public function testBackup()
    {
        $result = DBTool::backup();

        $this->assertFalse($result);

        (new \ZN\Database\DriverTool)->backup('*', NULL, self::default . 'package-database/resources/');
        (new \ZN\Database\DriverTool)->backup(['persons'], NULL, self::default . 'package-database/resources/');
        (new \ZN\Database\DriverTool)->backup('persons', NULL, self::default . 'package-database/resources/');
    }

    public function testImport()
    {
        $result = DBTool::import(self::default . 'package-database/resources/test.sql');

        $this->assertIsBool($result);

        $result = DBTool::import(self::default . 'unknown');

        $this->assertFalse($result);
    }
}