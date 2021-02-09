<?php namespace ZN\Authentication;

use DB;
use User;
use Config;
use DBForge;

class RegisterTest extends AuthenticationExtends
{
    public function testStandart()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        $row = DB::where('username', 'robot@znframework.com')->users()->row();

        $this->assertEquals('robot@znframework.com', $row->username);
    }

    public function testStandartWithAutoLogin()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ], true);

        $this->assertEquals('robot@znframework.com', User::data()->username);
    }

    public function testStandartWithAutoLoginRedirect()
    {
        Properties::$redirectExit = false;
        
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ], 'redirect/link');

        Properties::$redirectExit = true;
    }

    public function testStandartWithWithOptionalMethodAutoLogin()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::autoLogin()->register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ]);

        $this->assertEquals('robot@znframework.com', User::data()->username);
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

        $data = (new Data)->get('addresses');

        $this->assertEquals('London', $data->address);

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

    public function testUnknownUserInformation()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');
        
        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234',
            'unknown'  => 'value'
        ]);

        $this->assertEquals('Unknown error!', User::error());
    }

    public function testActivationColumnActivationReturnLinkNotFoundException()
    {
        DB::where('username', 'robot')->delete('accounts');

        DBForge::dropTable('accounts');

        DBForge::createTable('accounts',
        [
            'username'      => [DB::varchar(255)],
            'password'      => [DB::varchar(255)],
            'email'         => [DB::varchar(255)],
            'activation'    => [DB::varchar(255)]
        ]);

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'accounts',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => 'email',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => 'activation',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
            
        try
        {
            User::register
            ([
                'username' => 'robot',
                'password' => '1234',
                'email'    => 'robot@znframework.com'
            ]);
        }
        catch( Exception\ActivationReturnLinkNotFoundException $e )
        {
            $this->assertEquals('The return link must be specified for the activation process!', $e->getMessage());
        }

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => '',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
    }

    public function testActivationColumn()
    {
        DB::where('username', 'robot@znframework.com')->delete('accounts');

        DBForge::dropTable('accounts');

        DBForge::createTable('accounts',
        [
            'username'      => [DB::varchar(255)],
            'password'      => [DB::varchar(255)],
            'activation'    => [DB::varchar(255)]
        ]);

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'accounts',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => 'activation',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
            
        try
        {
            User::register
            ([
                'username' => 'robot@znframework.com',
                'password' => '1234'
            ], false, 'return/link');
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('is an invalid email address!', $e->getMessage());
        }

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => '',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
    }
}