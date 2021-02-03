<?php namespace ZN\Authentication;

use DB;
use User;

class InfoTest extends AuthenticationExtends
{
    public function testUserIP()
    {
        $this->assertIsString(User::ip());
    }

    public function testUserAgent()
    {
        $this->assertIsString(User::agent());
    }

    public function testUserCount()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);
        
        $this->assertSame(1, User::count());
    }

    public function testUserActiveCount()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        $this->assertEquals(1, User::activeCount());
    }

    public function testUserBannedCount()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        User::update('1234', '1234', NULL, ['banned' => 1]);

        $this->assertSame(1, User::bannedCount());
    }

    public function testGetEncryptionPassword()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        $data = User::data();

        $this->assertEquals($data->password, User::getEncryptionPassword('1234'));
    }
}