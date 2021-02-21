<?php namespace ZN\Authentication;

use DB;
use User;

class LogoutTest extends AuthenticationExtends
{ 
    public function testLogout()
    {
        User::logout();

        $this->assertFalse(User::isLogin());
    }

    public function testLogoutActiveColumn()
    {
        Properties::$redirectExit = false;
        
        DB::where('username', 'roboty@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'roboty@znframework.com',
            'password' => '1234'
        ], true);

        User::logout();
        
        $this->assertFalse(User::isLogin());

        Properties::$redirectExit = true;
    }
}