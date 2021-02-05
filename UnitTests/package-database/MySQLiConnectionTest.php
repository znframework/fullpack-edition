<?php namespace ZN\Database;

use DB;

class MySQLiConnectionTest extends DatabaseExtends
{
    const connection = ['driver' => 'mysqli', 'user' => 'root', 'host' => 'localhost', 'database' => 'test', 'password' => ''];

    public function testConnection()
    {
        #$db = new MySQLi\DB;

        #$db->connect(self::connection);
    }
}