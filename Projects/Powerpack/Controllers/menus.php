<?php namespace Project\Controllers;

use DBGrid, URI, Post, Generate, Method, Arrays, File, Folder, Config, Json, Users, DB, Menus as MenuModel;

class Menus extends Controller
{
    const extract = true;

    public function main()
    {
        if( URI::get('process') === 'add' && Post::saveButton() )
        {
            $controller = Method::post('menus:name');
            $view       = $oview = 'main';

            if( Config::viewObjects('viewNameType') === 'directory' )
            {
                $viewControllerDir = $controller . DS;

                Folder::create(VIEWS_DIR . $viewControllerDir);

                $view = $viewControllerDir . $view;
            }
            else
            {
                $view = $controller . '-' . $view;
            }

            $viewPath = VIEWS_DIR . suffix($view . '.wizard', '.php');

            if( ! File::exists($viewPath) )
            {
                File::write($viewPath, File::read(VIEWS_DIR . 'sections/content.wizard.php'));
            }
            
            $status = Generate::controller($controller,
            [
                'namespace'   => 'Project\Controllers',
                'extends'     => 'Controller',
                'functions'   => [$oview]
            ]);

            Masterpage::success('success');

            Users::activity('added menu');
        }

        $icons = Json::decodeArray(File::read(FILES_DIR . 'fa-icons.json'));

        View::pageTitle($this->dict->menus)->pageBody
        (
            DBGrid::columns('id as ID', 'name as Name', 'submenu_id as SubID', 'url as URL',  'icon as Icon', 'order_id as OrderID')
                  ->inputs
                  ([
                        'submenu_id' => function($form, $name, $value)
                        { 
                                return $form->query(DB::string()->where('submenu_id', 0)->menus())->select($name, ['id' => 'name', 0 => $this->dict->mainCategory], $value);
                        }, 
                        'icon' => function($form, $name, $value) use($icons)
                        { 
                                return $form->select($name, $icons, $value);
                        },
                        'active' => function($form, $name, $value)
                        { 
                                return $form->select($name, [0 => $this->dict->deactive, 1 => $this->dict->active], $value);
                        }
                  ])
                  ->outputs
                  ([
                        'submenu_id' => function($html, $value)
                        { 
                            $name = MenuModel::select('name')->where('id', $value)->row(true);

                            return $name ?: $this->dict->mainCategory;
                        }
                  ])
                  ->create('menus')
        );
    }
}
