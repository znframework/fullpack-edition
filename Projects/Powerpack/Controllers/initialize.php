<?php namespace Project\Controllers;

use User, URL, Http, DB, Post, Permission, Config, Lang, Redirect, URI;

class Initialize extends Controller
{
    const exclude = ['login', 'login/lockscreen', 'login/installation'];

    public function main(String $params = NULL)
    {   
        if( ! is_file(CONNECT_FILE) )
        {
            redirect('installation');
        }

        if( ! User::isLogin() )
        {
            redirect('login');
        }
        
        Masterpage::headPage('sections/head')
                  ->bodyPage('sections/body')
                  ->title
                  (
                      'ZN Powerpack &raquo; ' . 
                      ucfirst(CURRENT_CONTROLLER) . 
                      (CURRENT_CFUNCTION !== 'main' ? ' &raquo; ' . ucfirst(CURRENT_CFUNCTION) : '')
                  );

        View::user($user = User::data())->site(SITE_URL);

        $perms = DB::permissionsResult();
        
        $permissions = [];

        foreach( $perms as $perm )
        {
            if( $perm->rules === 'any' || $perm->rules === 'all' )
            {
                $nperm = $perm->rules;
            }
            else
            {
                $nperm = [$perm->type => explode('|', $perm->rules)];
            }

            $permissions[$perm->id] = $nperm;
        }

        Config::set('IndividualStructures', 'permission', ['page' => $permissions, 'method' => $permissions]);

        if( ! empty($permissions) && (! Permission::page($user->role_id) || ! Permission::request($user->role_id)))
        {
            Redirect::data(['error' => Lang::individualStructures('cache:authenticationFailed')])->action();
        }  
    }
}
