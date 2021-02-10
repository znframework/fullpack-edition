<?php namespace ZN\Database;

class PostgresToolTest extends DatabaseExtends
{ 
    public function testListDatabases()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertEquals('test', $tool->listDatabases()[1]);
    }

    public function testListTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertIsArray($tool->listTables());
    }

    public function testStatusTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->statusTables());
    }

    public function testOptimizeTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->optimizeTables());
    }

    public function testRepairTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->repairTables());
    }

    public function testBackup()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->backup());
    }
}