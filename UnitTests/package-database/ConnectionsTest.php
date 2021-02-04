<?php namespace ZN\Database;

use DB;

class ConnectionsTest extends DatabaseExtends
{
    public function testMySQLi()
    {
        $connection = ['driver' => 'mysqli', 'user' => 'root', 'host' => 'localhost', 'database' => 'test', 'password' => ''];

        $db = new MySQLi\DB;

        $db->connect($connection);
    }
}