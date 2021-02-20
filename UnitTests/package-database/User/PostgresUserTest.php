<?php namespace ZN\Database;

use DBUser;

class PostgresUserTest extends DatabaseExtends
{
    public function testCreate()
    {
        $user = DBUser::new(self::postgres);

        $user->drop('example');

        $this->assertTrue($user->create('example'));

        $user->drop('example');

        $this->assertTrue($user->password('password')->passwordExpire('2020-10-10')->create('example'));
    }

    public function testAlter()
    {
        $user = DBUser::new(self::postgres);

        $user->drop('example');

        $user->password('password')->create('example');

        $this->assertTrue($user->password('postgres')->alter('current'));
    }

    public function testDrop()
    {
        DBUser::new(self::postgres)->create('example');

        $this->assertTrue(DBUser::new(self::postgres)->drop('example'));
    }

    public function testGrant()
    {
        $user = DBUser::new(self::postgres);

        $user->drop('example');

        $user->password('password')->create('example');

        $this->assertTrue($user->select('test')->name('example')->grant('all'));
    }

    public function testRevoke()
    {
        $user = DBUser::new(self::postgres);

        $user->drop('example');

        $user->password('password')->create('example');

        $user->select('test')->name('example')->grant('all');

        $this->assertTrue($user->select('test')->name('example')->revoke('all'));
    }

    public function testRename()
    {
        $user = DBUser::new(self::postgres);

        $user->drop('example');

        $user->password('password')->create('example');

        $user->rename('example', 'example2');
    }
}