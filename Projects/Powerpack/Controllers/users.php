<?php namespace Project\Controllers;

use UserActivities, JC, Post, Users as UserModel, Http, User, DB, URI, Lang, DBGrid, Validation, Upload, Redirect, Form;

class Users extends Controller
{
    const extract = true;

    public function create()
    {
        View::pageTitle('Create Account')->pageBody('view:users/users-form');

        $this->ajaxCreateAccount();
    }

    public function list()
    {
        View::pageTitle('User List')->pageBody
        (
            DBGrid::exclude('email', 'password', 'date')
                  ->hide('addButton')
                  ->inputs(['gender' => function($form, $name, $value)
                  { 
                      return $form->select($name, [1 => $this->dict->male, 2 => $this->dict->female], $value);
                  }])
                  ->columns('id as ID', 'email as Email', 'name as Name', 'role_id as Role', 'date as Date')
                  ->create('users')
        );
    }

    public function profile(String $params = NULL)
    {      
        if( Post::editPhoto() )
        {
            $this->editPhoto();
        }  
        
        $activities = UserActivities::where('user_id', $this->user->id)->limit(Post::start(), 5)->orderBy('date', 'desc');

        View::activities($activities->result())->pagination(JC::pagination($activities, function($pagination)
        {
            $pagination->type('ajax');
        }))
        ->modalbox(JC::modalbox('modalID', function($modal)
        {
            $modal->title('Edit Profile');
            $modal->content(View::get('users/profile-form', true));
            $modal->process(function($form)
            {
                echo $form->class('btn btn-primary')->onclick('ajaxEdit()')->button('edit', 'EDIT');
            });
        }));

        if( Post::ajaxEdit() )
        {
            $this->ajaxEdit();     
        }
    }

    protected function editPhoto()
    {
        Upload::settings
        ([
            'prefix'     => md5($this->user->id),
            'extensions' => 'jpg|png|gif',
            'maxsize'    => 1000000
        ])->start('img', FILES_DIR . 'photos/');
        
        if( ! $error = Upload::error() )
        {
            $info = Upload::info('encodeName');

            UserModel::updateId(['photo' => $info], $this->user->id);

            UserModel::activity('edited profile photo');

            Redirect::data(['success' => 'success'])->action('profile');
        }
    }

    protected function ajaxCreateAccount()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }
  
        Validation::rules('email', ['email'], $this->dict->email);
        Validation::rules('password', ['minchar' => 4, 'maxchar' => 16, 'matchPassword' => 'passwordAgain'], $this->dict->password);

        if( ! $string = Validation::error('string') )
        {
            if( User::register(['email' => Post::email(), 'password' => Post::password(), 'role_id' => 2]) )
            {
                Masterpage::success('success');
                UserModel::activity('created account');
            }
            else
            {
                Masterpage::error(User::error());
            }
        }
        else
        {
            Masterpage::error($string);
        }  
    }

    protected function ajaxEdit()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        UserModel::where('id', $this->user->id)->update('post');

        UserModel::activity('updated profile');

        View::user($this->user = User::data());

        Masterpage::success('success');
    }
}
