<?php namespace ZN\Authentication;

use DB;
use User;
use DBForge;

class RegisterActivationInvalidEmailExceptionTest extends AuthenticationExtends
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
                'password' => '1234'
    
            ], false, 'return/link');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('[robotz] parameter is an invalid email address!', $e->getMessage());
        }
        
        DBForge::dropTable('accounts');

        $this->defaultConfig();
    }
}