<?php namespace Project\Controllers;

use Validation, Post, Arrays, Config, File, User, Users, Generate, Json, Cookie, Migration, DB;

class Login extends Controller
{
    public function __construct()
    {
        if( is_file(CONNECT_FILE) )
            if( User::isLogin() && CURRENT_CFUNCTION !== 'logout')
                redirect();
    }

    public function installation(String $params = NULL)
    {
        if( is_file(CONNECT_FILE) )
        {
            redirect('login');
        }

        if( Post::install() )
        {
            $post = Arrays::remove(Post::all(), 'install', NULL);

            Config::set('Database', 'database', $post);

            DB::multiQuery(File::read(STORAGE_DIR . 'DatabaseBackup/default.sql'));

            $ok = User::register
            ([
                'email'    => 'admin@powerpack.com',
                'password' => 'powerpack',
                'name'     => 'Admin',
                'role_id'  => 1
            ]);

            File::write(CONNECT_FILE, Json::encode($post));
         
            redirect('login'); 
        }

        View::version(Config::dashboard('version'));
    }

    public function main(String $params = NULL)
    {
        View::config(Config::dashboard());

        if( Post::login() )
        {
            if( User::login(Post::email(), Post::password(), (bool) Post::remember()) )
            {
                $data = User::data();

                Cookie::photo($data->photo);
                Cookie::name($data->name);

                Users::activity('signed in');

                redirect();
            }
        }
    }

    public function lockscreen(String $params = NULL)
    {
        if( Post::lockscreen() )
        {   
            Validation::rules('email', ['required', 'email'], 'Email');

            if( ! $error = Validation::error('string') )
            {
                User::forgotPassword(Post::email(), 'login');
                
                if( $userError = User::error() )
                {
                    View::status($userError);
                }   
                else
                {
                    View::status(User::success())->info('success');
                } 
            }
            else
            {
                View::status($error);
            }
        }
    }

    public function logout()
    {
        Users::activity('signed out');

        User::logout('login');
    }
}
