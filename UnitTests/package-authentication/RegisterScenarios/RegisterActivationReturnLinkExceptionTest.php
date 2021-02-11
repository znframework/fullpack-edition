<?php namespace ZN\Authentication;

use DB;
use User;
use Config;
use DBForge;

class RegisterActivationReturnLinkExceptionTest extends AuthenticationExtends
{
    public function testMake()
    {
        $this->activationConfig();

        DB::where('username', 'robotz')->delete('accounts');

        DBForge::createTable('accounts',
        [
            'username'   => [DB::varchar(255)],
            'password'   => [DB::varchar(255)],
            'email'      => [DB::varchar(255)],
            'activation' => [DB::int(1)]
        ]);

        try
        {
            (new Register)->do
            ([
                'username' => 'robotz',
                'password' => '1234',
                'email'    => 'robot@znframework.com'
    
            ]);
        }
        catch( \Exception $e )
        {
            $this->assertEquals('The return link must be specified for the activation process!', $e->getMessage());
        }

        DBForge::dropTable('accounts');

        $this->defaultConfig();
    }
}