<?php namespace ZN\Database;

use DB;
use DBTool;
use DBForge;

class ConnectionsTest extends DatabaseExtends
{
    public function testMySQLi()
    {
        $connection = ['driver' => 'mysqli', 'user' => 'root', 'host' => 'localhost', 'database' => 'test', 'password' => ''];

        $forge = DBForge::new($connection);
        $tool  = DBTool::new($connection);

        $forge->createTable('IF NOT EXISTS persons',
        [
            'id'      => [DB::int(11), DB::primaryKey(), DB::autoIncrement()],
            'name'    => [DB::varchar(255)],
            'surname' => [DB::varchar(255)],
            'phone'   => [DB::varchar(255)]
        ]);

        print_r($tool->listTables());
    }
}