<?php namespace ZN\Database;

class PostgresTriggerTest extends DatabaseExtends
{ 
    public function testRepairTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->repairTables());
    }

    public function testBody()
    {
        $this->assertNull((new Postgres\DBTrigger)->body('id'));
        $this->assertNull((new Postgres\DBTrigger)->body(['id']));
    }

    public function testDropTrigger()
    {
        $this->assertEquals('DROP TRIGGER ON test ex;', (new Postgres\DBTrigger)->dropTrigger('test', 'ex'));
    }
}