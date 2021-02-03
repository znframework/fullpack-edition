<?php namespace ZN\Authentication;

use DB;
use User;

class LoginTest extends AuthenticationExtends
{ 
    public function testStandartLogin()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        $this->assertTrue(User::login('robot@znframework.com', '1234'));
    }

    public function testIsLogin()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');
        
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        $this->assertTrue(User::isLogin());
    }

    public function testData()
    {
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        $this->assertEquals('robot@znframework.com', User::data()->username);

        DB::where('username', 'robot@znframework.com')->delete('users');
    }
}