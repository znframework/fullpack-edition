<?php namespace ZN\Database;

use DB;
use Config;
use DBForge;

class DatabaseExtends extends \ZN\Test\GlobalExtends
{
    protected $persons;
    
    public function __construct()
    {
        parent::__construct();

        Config::database('database', 
        [
            'driver'   => 'sqlite',
            'database' => self::default . 'package-database/resources/testdb',
            'password' => '1234'
        ]);

        DBForge::createTable('IF NOT EXISTS persons',
        [
            'name'    => [DB::varchar(255)],
            'surname' => [DB::varchar(255)],
            'phone'   => [DB::varchar(255)]
        ]);

        $this->persons = new Class() extends GrandModel
        {
            const table      = 'persons';
            const facade     = 'ZN\Database\Test\Persons';
            const connection = 
            [
                'driver'   => 'sqlite',
                'database' => \ZN\Test\GlobalExtends::default . 'package-database/testdb',
                'password' => '1234'
            ];
        };
    }

    public function mysqli(&$db = NULL)
    {
        $db = new MySQLi\DB;

        $db->connect(['driver' => 'mysqli', 'user' => 'root', 'host' => 'localhost', 'database' => 'test', 'password' => '']);
    }
}