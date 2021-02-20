<?php namespace ZN\Database;

use DBUser;

class MySQLiUserTest extends DatabaseExtends
{
    public function testCreate()
    {
        DBUser::new(self::mysqli)->drop('example@localhost');

        $this->assertTrue(DBUser::new(self::mysqli)->create('example@localhost'));

        DBUser::new(self::mysqli)->drop('example@localhost');

        $this->assertTrue(DBUser::new(self::mysqli)->password('password')->passwordExpire('2100-10-10')->create('example@localhost'));
    }

    public function testAlter()
    {
        DBUser::new(self::mysqli)->drop('example@localhost');

        DBUser::new(self::mysqli)->password('password')->create('example@localhost');

        $this->assertTrue(DBUser::new(self::mysqli)->password('newpassword')->alter('example@localhost'));
    }

    public function testDrop()
    {
        DBUser::new(self::mysqli)->create('example@localhost');

        $this->assertTrue(DBUser::new(self::mysqli)->drop('example@localhost'));
    }

    public function testGrant()
    {
        DBUser::new(self::mysqli)->drop('example@localhost');

        DBUser::new(self::mysqli)->password('password')->create('example@localhost');

        $this->assertTrue(DBUser::new(self::mysqli)->select('test')->name('example@localhost')->grant('all'));
    }

    public function testRevoke()
    {
        DBUser::new(self::mysqli)->drop('example@localhost');

        DBUser::new(self::mysqli)->password('password')->create('example@localhost');

        DBUser::new(self::mysqli)->select('test')->name('example@localhost')->grant('all');

        $this->assertTrue(DBUser::new(self::mysqli)->select('test')->name('example@localhost')->revoke('all'));
    }

    public function testRename()
    {
        DBUser::new(self::mysqli)->drop('example@localhost');

        DBUser::new(self::mysqli)->password('password')->create('example@localhost');

        DBUser::new(self::mysqli)->rename('example@localhost', 'example@127.0.0.1');
    }
}