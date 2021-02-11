<?php namespace ZN\Authentication;

use DB;
use User;
use DBForge;

class RegisterActivationSendEmailErrorTest extends AuthenticationExtends
{
    public function testMake()
    {
        $this->activationConfig();

        DB::where('username', 'robotz@znframework.com')->delete('accounts');

        DBForge::createTable('accounts',
        [
            'username'   => [DB::varchar(255)],
            'password'   => [DB::varchar(255)],
            'activation' => [DB::int(1)]
        ]);

        $register = new Register;

        $register->do
        ([
            'username' => 'robotz@znframework.com',
            'password' => '1234'

        ], false, 'return/link');

        DBForge::dropTable('accounts');

        $this->defaultConfig();
    }
}