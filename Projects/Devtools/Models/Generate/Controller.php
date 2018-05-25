<?php namespace Generate;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use File;
use Folder;
use Validation;
use Method;
use Arrays;
use URI;
use Redirect;
use Masterpage;
use Generate as Gen;
use ZN\Base;
use ZN\Model;

class Controller extends Model
{
    public static function sendMasterpageData()
    {
        Masterpage::pdata
        ([
            'content'    => 'controller',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . ($path = 'Controllers')),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    public static function run()
    {
        Validation::rules('controller', ['required', 'alnum'], LANG['controllerName']);

        if( ! $error = Validation::error('string') )
        {
            $functions = Method::post('functions');
            $functions = explode(',', empty($functions) ? 'main' : $functions);

            if( ! Arrays::valueExists($functions, 'main') )
            {
                $functions = Arrays::addFirst($functions, 'main');
            }

            $viewObjectConfig = Base::import(SELECT_PROJECT_DIR . 'Config' . DS . 'Starting.php');

            $controller = Method::post('controller');

            if( $type = Method::post('withView') )
            {
                foreach( $functions as $view )
                {
                    $view = trim($view);

                    $viewsDir = SELECT_PROJECT_DIR . 'Views' . DS;

                    if( ($viewObjectConfig['viewNameType'] ?? NULL) === 'directory' )
                    {
                        $viewControllerDir = $controller . DS;

                        Folder::create($viewsDir . $viewControllerDir);

                        $view = $viewControllerDir . $view;
                    }
                    else
                    {
                        if( $view === 'main' )
                        {
                            $view = $controller;
                        }

                        $view = $controller . '-' . $view;
                    }

                    $viewPath = $viewsDir . Base::suffix($view . ( $type === 'wizard' ? '.wizard' : NULL ), '.php');

                    if( ! File::exists($viewPath) )
                    {
                        File::create($viewPath);
                    }
                }
            }

            $status = Gen::controller($controller,
            [
                'application' => SELECT_PROJECT,
                'namespace'   => 'Project\Controllers',
                'extends'     => 'Controller',
                'functions'   => $functions
            ]);

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}