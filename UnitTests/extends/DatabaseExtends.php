<?php namespace ZN\Database;

use DB;
use Config;
use DBForge;

class DatabaseExtends extends \ZN\Test\GlobalExtends
{
    protected $persons;

    const sqlite   = ['driver'   => 'sqlite', 'database' => self::default . 'package-database/resources/testdb', 'password' => '1234'];
    const mysqli   = ['driver' => 'mysqli'  , 'user' => 'mysqli'  , 'host' => 'localhost', 'database' => 'test', 'password' => 'mysqli'  , 'port' => 3306];
    const postgres = ['driver' => 'postgres', 'user' => 'postgres', 'host' => 'localhost', 'database' => 'test', 'password' => 'postgres', 'port' => 5432];

    public function __construct()
    {
        parent::__construct();

        Config::database('database', self::sqlite);

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

    protected function driver($callback, $driver = 'postgres')
    {
        try
        {
            $class = 'ZN\Database\\' . $driver . '\\DB';

            $db = new $class;

            $db->connect(constant('self::' . strtolower($driver)));

            if( $callback !== NULL )
                $callback($db);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            echo $e->getMessage();
        }
    }

    protected function postgres($callback = NULL)
    {
        $this->driver($callback, 'Postgres');
    }

    protected function mysqli($callback = NULL)
    {
        $this->driver($callback, 'MySQLi');
    }
}