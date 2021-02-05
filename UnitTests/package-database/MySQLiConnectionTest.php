<?php namespace ZN\Database;

class MySQLiConnectionTest extends DatabaseExtends
{
    const connection = ['driver' => 'mysqli', 'user' => '', 'host' => 'localhost', 'database' => 'test', 'password' => '', 'port' => 3306];

    public function testConnection()
    {
        try
        {
            $db = new MySQLi\DB;

            $db->connect(self::connection);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            $this->assertIsString($e->getMessage());
        }
        
    }
}