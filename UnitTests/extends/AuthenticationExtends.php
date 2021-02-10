<?php namespace ZN\Authentication;

use DB;
use Config;
use DBForge;

class AuthenticationExtends extends \ZN\Test\GlobalExtends
{
    public function __construct()
    {
        parent::__construct();

        Config::database('database', 
        [
            'driver'   => 'sqlite',
            'database' => self::default . 'package-authentication/resources/testdb',
            'password' => '1234'
        ]);

        DBForge::createTable('IF NOT EXISTS users',
        [
            'username'          => [DB::varchar(255)],
            'password'          => [DB::varchar(255)],
            'phone'             => [DB::varchar(20)],
            'active'            => [DB::int(1)],
            'banned'            => [DB::int(1)],
            'activation'        => [DB::int(1)],
            'verification'      => [DB::varchar(20)]
        ]);
        
        Config::set('Auth', 
        [
            'encode'    => 'gost',
            'spectator' => '',
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
            ],
            'joining' =>
            [
                'column' => '',
                'tables' => []
            ],
            'emailSenderInfo' =>
            [
                'name' => 'Robot',
                'mail' => 'robot@znframework.com'
            ]
        ]);

        $this->userExtendsMock = new Class extends UserExtends
        {
            public function mockGetEmailTemplate()
            {
                $this->setEmailTemplate('message {user}, {pass}, {url}');
                
                return $this->getEmailTemplate(['user' => 'userx', 'pass' => 'passx', 'url' => 'urlx'], '');
            }

            public function mockAutoMatchColumns($data)
            {
                $this->autoMatchColumns($data);
            }

            public function mockGetUserTableColumns()
            {
                return $this->getUserTableColumns();
            }
        };

        $this->loginMock = new Class extends Login
        {
            public function mockUserExists()
            {
                return $this->userExists('robot@znframework.com', '1234');
            }

            public function mockStartPermanentUserSessionWithCookie()
            {
                return $this->startPermanentUserSessionWithCookie('robot@znframework.com', '1234');
            }
        };  
    }
}