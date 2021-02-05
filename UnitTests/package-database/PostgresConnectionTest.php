<?php namespace ZN\Database;

use DB;

class PostgresConnectionTest extends DatabaseExtends
{ 
    const connection = ['driver' => 'postgres', 'user' => 'postgres', 'host' => 'localhost', 'database' => 'test', 'password' => 'postgres', 'port' => 5432];

    public function testConnection()
    {
        $db = new Postgres\DB;

        $db->connect(self::connection);
    }
}