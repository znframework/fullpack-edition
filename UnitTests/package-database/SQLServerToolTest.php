<?php namespace ZN\Database;

class SQLServerToolTest extends DatabaseExtends
{ 
    public function testListDatabases()
    {
        (new SQLServer\DBTool)->listDatabases();
    }

    public function testListTable()
    {
        (new SQLServer\DBTool)->listTables();
    }

    public function testStatusTable()
    {
        (new SQLServer\DBTool)->statusTables('table');
    }

    public function testOptimizeTable()
    {
        (new SQLServer\DBTool)->optimizeTables('table');
    }

    public function testRepairTable()
    {
        (new SQLServer\DBTool)->repairTables('table');
    }

    public function testBackup()
    {
        (new SQLServer\DBTool)->backup('table', 'name', 'path');
    }
}