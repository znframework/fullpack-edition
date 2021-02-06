<?php namespace ZN\Database;

class PostgresUserTest extends DatabaseExtends
{ 
    public function testUserName()
    {
        $this->assertNull((new Postgres\DBUser)->name('test'));
    }

    public function testUserCreate()
    {
        $this->assertEquals('CREATE USER test', (new Postgres\DBUser)->create('test'));
    }

    public function testUserDrop()
    {
        $this->assertEquals('DROP USER test', (new Postgres\DBUser)->drop('test'));
    }

    public function testUserAlter()
    {
        $user = new Postgres\DBUser;

        $user->option('PASSWORD', 'test');

        $this->assertEquals('ALTER USER test PASSWORD test', $user->alter('test'));
    }
}