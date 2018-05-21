<?php namespace Project\Controllers;

use Import;
use Method;
use Session;
use Redirect;

class Login extends Controller
{
    /**
     * Main Page
     */
    public function main(String $params = NULL)
    {
        if( Session::select('isLogin') )
        {
            Redirect::location('logout');
        }

        $users = DASHBOARD_CONFIG['users'];

        if( Method::post('login') )
        {
            if( ($users[Method::post('user')] ?? NULL) === Method::post('password') )
            {
                Session::insert('isLogin', 1);
                Session::insert('username', Method::post('user'));
                Session::insert('password', Method::post('password'));

                Redirect::location();
            }
        }

        Import::view('login');
    }

    /**
     * Out
     */
    public function out()
    {
        Session::delete('isLogin');
        Session::delete('username');
        Session::delete('password');

        Redirect::location('login');
    }
}
