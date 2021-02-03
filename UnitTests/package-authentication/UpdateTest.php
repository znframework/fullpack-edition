<?php namespace ZN\Authentication;

use DB;
use User;

class UpdateTest extends AuthenticationExtends
{
    public function testUpdateOnlyPassword()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');
        
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        User::login('robot@znframework.com', '1234');

        $this->assertTrue(User::update('1234', '1234')); 
    }
}