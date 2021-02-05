<?php namespace ZN\Database;

use DB;

class MySQLiConnectionTest extends DatabaseExtends
{
    public function testConnection()
    {
        $this->mysqli();
    }

    public function testExec()
    {
        $this->mysqli($db);

        $db->exec('SELECT * FROM persons');

        $db->close();
    }
}