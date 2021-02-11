<?php namespace ZN\Authentication;

use DB;
use User;
use Config;
use DBForge;

class RegisterActivationWithUsernameColumnTest extends AuthenticationExtends
{
    public function testMake()
    {
        $this->activationConfig();

        DB::where('username', 'robot@znframework.com')->delete('accounts');

        DBForge::createTable('accounts',
        [
            'username'   => [DB::varchar(255)],
            'password'   => [DB::varchar(255)],
            'activation' => [DB::int(1)]
        ]);

        (new Register)->do
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'

        ], false, 'return/link');

        $this->assertEquals('For the completion of your registration, please click on the activation link sent to your e-mail address.', User::success());

        DBForge::dropTable('accounts');

        $this->defaultConfig();
    }
}