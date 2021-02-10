<?php namespace ZN\Database;

use DBUser;

class UserTest extends DatabaseExtends
{
    public function testCreate()
    {
        DBUser::create('zntr@localhost');

        $this->assertSame("CREATE USER  'zntr'@'localhost'", trim(DBUser::stringQuery()));
    }

    public function testCreateWithPassword()
    {
        DBUser::password('998891')->create('zntr@localhost');

        $this->assertSame("CREATE USER  'zntr'@'localhost'  IDENTIFIED BY  '998891'", trim(DBUser::stringQuery()));
    }

    public function testDrop()
    {
        DBUser::drop('zntr@localhost');

        $this->assertSame("DROP USER  'zntr'@'localhost'", trim(DBUser::stringQuery()));
    }

    public function testAlterWithPassword()
    {
        DBUser::password('998891')->alter('zntr@localhost');

        $this->assertSame("ALTER USER  'zntr'@'localhost'  IDENTIFIED BY  '998891'", trim(DBUser::stringQuery()));
    }

    public function testGrant()
    {
        DBUser::select('db1.*')->name('zn@localhost')->grant('all');

        $this->assertSame("GRANT  all ON  db1.* TO  'zn'@'localhost'", trim(DBUser::stringQuery()));
    }

    public function testRevoke()
    {
        DBUser::select('*.*')->name('zn@localhost')->revoke('insert');

        $this->assertSame("REVOKE  insert ON  *.* FROM  'zn'@'localhost'", trim(DBUser::stringQuery()));
    }

    public function testRename()
    {
        DBUser::rename('zn@localhost', 'zn@127.0.0.1');

        $this->assertSame("RENAME USER  'zn'@'localhost'  TO  'zn'@'127.0.0.1'", trim(DBUser::stringQuery()));
    }

    public function testHost()
    {
        $user = new \ZN\Database\DBUser;

        $user->host('localhost');
    }

    public function testIdentifiedBy()
    {
        $user = new \ZN\Database\DBUser;

        $user->identifiedBy('root');
    }

    public function testIdentifiedByPassword()
    {
        $user = new \ZN\Database\DBUser;

        $user->identifiedByPassword('root');
    }

    public function testIdentifiedWith()
    {
        $user = new \ZN\Database\DBUser;

        $user->identifiedWith('auth', 'type', 'string');
    }

    public function testIdentifiedWithBy()
    {
        $user = new \ZN\Database\DBUser;

        $user->identifiedWithBy('auth', 'string');
    }

    public function testIdentifiedWithAs()
    {
        $user = new \ZN\Database\DBUser;

        $user->identifiedWithAs('plugin', 'string');
    }

    public function testRequired()
    {
        $user = new \ZN\Database\DBUser;

        $user->required();
    }

    public function testWith()
    {
        $user = new \ZN\Database\DBUser;

        $user->with();
    }

    public function testOption()
    {
        $user = new \ZN\Database\DBUser;

        $user->option('name', 'value');
    }

    public function testEncode()
    {
        $user = new \ZN\Database\DBUser;

        $user->encode('type', 'string', 'condition');
    }

    public function testResource()
    {
        $user = new \ZN\Database\DBUser;

        $user->resource('resource', 1);
    }

    public function testPasswordExpire()
    {
        $user = new \ZN\Database\DBUser;

        $user->passwordExpire('type', 1);
    }

    public function testLock()
    {
        $user = new \ZN\Database\DBUser;

        $user->lock('lock');
    }

    public function testUnlock()
    {
        $user = new \ZN\Database\DBUser;

        $user->unlock('unlock');
    }

    public function testType()
    {
        $user = new \ZN\Database\DBUser;

        $user->type('TABLE');
    }

    public function testGrantOption()
    {
        $user = new \ZN\Database\DBUser;

        $user->grantOption();
    }

    public function testSetPassword()
    {
        $user = new \ZN\Database\DBUser;

        $user->setPassword('user', 'pass');
    }
}