<?php namespace ZN\Database;

class MySQLiConnectionTest extends DatabaseExtends
{
    const connection = ['driver' => 'mysqli', 'user' => 'user', 'host' => 'localhost', 'database' => 'test', 'password' => 'password'];

    public function testConnection()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);
    }
}