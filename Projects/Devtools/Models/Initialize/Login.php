<?php namespace Initialize;

use Arrays;
use User;
use Session;
use Redirect;
use ZN\Model;

class Login extends Model
{
    public static function control()
    {
        if( strtolower(CURRENT_CONTROLLER) !== 'login' && ! Arrays::valueExists(DASHBOARD_CONFIG['ip'], User::ip()) && ! Session::select('isLogin') )
        {
            Redirect::location('login');
        }
    }
}