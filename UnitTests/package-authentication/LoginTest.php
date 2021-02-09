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

    public function testUsername()
    {
        User::username('example@example.com');

        $this->assertEquals('example@example.com', Properties::$parameters['username']);
    }

    public function testPassword()
    {
        User::password('1234');

        $this->assertEquals('1234', Properties::$parameters['password']);
    }

    public function testDoFalse()
    {
        $this->assertFalse((new Login)->do('example@example.com', '1234', []));
    }

    public function testDoLoginError()
    {
       (new Login)->do('example22@example.com', '1234', []);

        $this->assertEquals('Login failed. The user name or password is incorrect!', User::error());
    }
}