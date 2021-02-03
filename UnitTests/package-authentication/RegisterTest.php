<?php namespace ZN\Authentication;

use DB;
use User;
use Config;
use DBForge;

class RegisterTest extends AuthenticationExtends
{
    public function testStandart()
    {
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        $row = DB::where('username', 'robot@znframework.com')->users()->row();

        $this->assertEquals('robot@znframework.com', $row->username);

        DB::where('username', 'robot@znframework.com')->delete('users');
    }

    public function testStandartWithAutoLogin()
    {
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ], true);

        $this->assertEquals('robot@znframework.com', User::data()->username);

        DB::where('username', 'robot@znframework.com')->delete('users');
    }

    public function testStandartWithWithOptionalMethodAutoLogin()
    {
        User::autoLogin()->register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ]);

        $this->assertEquals('robot@znframework.com', User::data()->username);

        DB::where('username', 'robot@znframework.com')->delete('users');
    }

    public function testJoinColumn()
    {
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

        User::register
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

        User::login('robot@znframework.com', '1234');

        $data = User::data('addresses');

        $this->assertEquals('London', $data->address ?? 'London');

        DB::where('username', 'robot@znframework.com')->delete('users');

        DBForge::dropTable('addresses');

        Config::set('Auth', 
        [
            'joining' =>
            [
                'column' => '',
                'tables' => []
            ]
        ]);
    }
}