<?php namespace ZN\Authentication;

use DB;
use DBForge;
use User;
use Config;

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

    public function testWrongPassword()
    {
        (new Login)->do('robot@znframework.com', '1234');

        (new Update)->do('12345');

        $this->assertEquals('You have entered the wrong password!', User::error());
    }

    public function testPasswordsDoNotMatch()
    {
        (new Login)->do('robot@znframework.com', '1234');

        (new Update)->do('1234', '123456', '1234');

        $this->assertEquals('Passwords do not match!', User::error());
    }

    public function testJoinColumn()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        DBForge::createTable('addresses',
        [
            'username' => [DB::varchar(255)],
            'address'  => [DB::varchar(255)]
        ]);

        Config::set('Auth', 
        [
            'joining' =>
            [
                'column' => 'username',
                'tables' => ['addresses' => 'username']
            ]
        ]);

        (new Register)->do
        ([
            'users' => 
            [
                'username' => 'robot@znframework.com',
                'password' => '1234'
            ],
            'addresses' => 
            [
                'address' => 'London'
            ]
        ]);

        (new Login)->do('robot@znframework.com', '1234');

        (new Update)->do('1234', '1234', '1234', 
        [
            'addresses' => 
            [
                'address' => 'Paris'
            ]
        ]);

        (new Login)->do('robot@znframework.com', '1234');

        $data = (new Data)->get('addresses');

        $this->assertEquals('Paris', $data->address);
    }

    public function testOldPassword()
    {
        (new Update)->oldPassword('1234');

        $this->assertEquals('1234', Properties::$parameters['oldPassword']);
    }

    public function testNewPassword()
    {
        (new Update)->newPassword('1234');

        $this->assertEquals('1234', Properties::$parameters['newPassword']);
    }

    public function testPasswordAgain()
    {
        (new Update)->passwordAgain('1234');

        $this->assertEquals('1234', Properties::$parameters['passwordAgain']);
    }
}