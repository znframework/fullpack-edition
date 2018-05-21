<?php namespace Project\Controllers;

use Method;
use Arrays;
use Generate as Gen;
use Redirect;
use Validation;
use Folder;
use File;
use Config;
use URI;
use URL;
use Security;
use Http;
use ZN\Base;

class Generate extends Controller
{
    /**
     * Generate Controller
     */
    public function controller(String $params = NULL)
    {
        if( Method::post('generate') )
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

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Controllers';

        Masterpage::page('generate');
        Masterpage::pdata
        ([
            'content'    => 'controller',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . $path),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    /**
     * Generate Library
     */
    public function library(String $params = NULL)
    {
        if( Method::post('generate') )
        {
            Validation::rules('library', ['required', 'alnum'], LANG['libraryName']);

            if( ! $error = Validation::error('string') )
            {
                $functions = explode(',', Method::post('functions'));

                if( ! Arrays::valueExists($functions, 'main') )
                {
                    $functions = Arrays::addFirst($functions, 'main');
                }

                $status = Gen::library(Method::post('library'),
                [
                    'application' => SELECT_PROJECT,
                    'functions'   => $functions
                ]);

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Libraries';

        Masterpage::page('generate');
        Masterpage::pdata
        ([
            'content'    => 'library',
            'title'      => 'libraries',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . $path),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    /**
     * Generate Command
     */
    public function command(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            Validation::rules('command', ['required', 'alnum'], LANG['commandName']);

            if( ! $error = Validation::error('string') )
            {
                $functions = explode(',', Method::post('functions'));

                if( ! Arrays::valueExists($functions, 'main') )
                {
                    $functions = Arrays::addFirst($functions, 'main');
                }

                $status = Gen::command(Method::post('command'),
                [
                    'application' => SELECT_PROJECT,
                    'namespace'   => 'Project\Commands',
                    'extends'     => 'Command',
                    'functions'   => $functions
                ]);

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Commands';

        Masterpage::page('generate');
        Masterpage::pdata
        ([
            'content'    => 'command',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . $path),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    /**
     * Generate Route
     */
    public function route(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        $path = 'Routes';

        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Method::post('generate') )
        {
            Validation::rules('route', ['required', 'alnum'], LANG['routeName']);

            if( ! $error = Validation::error('string') )
            {
                $functions = explode(',', Method::post('functions'));

                $routePath = $fullPath . Base::suffix(Base::prefix(Method::post('route')), '.php');

                if( ! File::exists($routePath) )
                {
                    File::create($routePath);
                }

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        Masterpage::page('generate');

        $pdata['content']    = 'route';
        $pdata['deletePath'] = $path;
        $pdata['files']      = Folder::allFiles($fullPath, true);

        Masterpage::pdata($pdata);  
    }

    /**
     * Generate Config
     */
    public function config(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        $path = 'Config';

        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Method::post('generate') )
        {
            Validation::rules('config', ['required', 'alnum'], LANG['configName']);

            if( ! $error = Validation::error('string') )
            {
                $functions = explode(',', Method::post('functions'));

                $configContent = '<?php return' . EOL . '[' . EOL . HT . '\'key\' => \'value\'' . EOL . '];';

                $configPath = $fullPath . Base::suffix(Base::prefix(Method::post('config')), '.php');

                if( ! File::exists($configPath) )
                {
                    File::write($configPath, $configContent);
                }

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        Masterpage::page('generate');
        $pdata['content']    = 'config';
        $pdata['deletePath'] = $path;

        $files = Folder::allFiles($fullPath, true);

        if( defined('SETTINGS_DIR') )
        {
            $settings = Folder::allFiles(SETTINGS_DIR);
        }
        else
        {
            $settings =  'Projects' . DS . 'Projects.php';
        }

        $files = Arrays::addFirst($files, $settings);

        $pdata['files']      = $files;

        Masterpage::pdata($pdata);
    }

    /**
     * Generate Model
     */
    public function model(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            Validation::rules('model', ['required', 'alnum'], LANG['modelName']);

            if( ! $error = Validation::error('string') )
            {
                $functions = explode(',', Method::post('functions'));

                $extends = Method::post('extends');

                $data =  
                [
                    'application' => SELECT_PROJECT,
                    'namespace'   => Method::post('namespace'),
                    'extends'     => $extends,
                    'functions'   => $functions
                ];

                if( $extends === 'RelevanceModel' )
                {
                    $data['constants'] = ['relevance' => '[/*first_table.column:second_table.column*/]'];
                }

                $status = Gen::model(Method::post('model'), $data);

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Models';

        Masterpage::page('generate');
        $pdata['content']    = 'model';
        $pdata['deletePath'] = $path;
        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Folder::exists($fullPath) )
        {
            $files = Folder::allFiles($fullPath, true);
        }

        $pdata['files'] = $files ?? [];

        Masterpage::pdata($pdata);
    }

    /**
     * Generate View
     */
    public function view(String $params = NULL)
    {
        $templates = Folder::files(TEMPLATES_DIR, 'php');
        $templates = Arrays::combine($templates, $templates);
        $templates['none'] = 'none';


        define('VIEW_TEMPLATES', $templates);

        if( Method::post('generate') )
        {
            Validation::rules('view', ['required'], LANG['viewName']);

            if( ! $error = Validation::error('string') )
            {
                $viewName = Method::post('view');

                if( Method::post('type') === 'Wizard' )
                {
                    $viewName = Base::suffix($viewName, '.wizard');
                }

                $viewPath = SELECT_PROJECT_DIR . 'Views/' . Base::suffix($viewName, '.php');
                $template = Method::post('template');

                if( $template === 'none' )
                {
                    $content = '';
                }
                else
                {
                    $content = File::read(TEMPLATES_DIR.$template);
                }

                Folder::create(File::pathInfo($viewPath, 'dirname'));

                if( ! File::exists($viewPath) )
                {
                    File::write($viewPath, $content);
                }

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Views';

        Masterpage::page('generate');
        $pdata['content'] = 'view';
        $pdata['deletePath'] = $path;
        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Folder::exists($fullPath) )
        {
            $files = Folder::allFiles($fullPath, true);
        }

        $pdata['files'] = $files ?? [];
        Masterpage::pdata($pdata);
    }

    /**
     * Generate Starting File
     */
    public function starting(String $params = NULL)
    {
        if( Method::post('generate') )
        {
            Validation::rules('file', ['required'], LANG['fileName']);

            if( ! $error = Validation::error('string') )
            {
                $viewName = Method::post('file');

                $path = Method::post('type') . DS;

                $path = 'Starting' . DS . $path;

                $viewPath = SELECT_PROJECT_DIR . $path . Base::suffix($viewName, '.php');

                if( ! File::exists($viewpath) )
                {
                    File::write($viewPath, '<?php');
                }

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Starting';

        Masterpage::page('generate');
        $pdata['content'] = 'starting';
        $pdata['deletePath'] = $path;
        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Folder::exists($fullPath) )
        {
            $files = Folder::allFiles($fullPath, true);
        }

        $pdata['files'] = $files ?? [];

        Masterpage::pdata($pdata);
    }

    /**
     * Generate Migration
     */
    public function migration(String $params = NULL)
    {
        if( IS_CONTAINER )
        {
            Redirect::location();
        }

        if( Method::post('generate') )
        {
            Validation::rules('migration', ['required', 'alnum'], LANG['migrationName']);

            if( ! $error = Validation::error('string') )
            {
                $path = PROJECTS_DIR . SELECT_PROJECT . DS . 'Models/Migrations/';

                \Migration::path($path)->create(Method::post('migration'), (int) Method::post('version'));

                Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
        }

        $path = 'Models'. DS .'Migrations';
        Masterpage::page('generate');
        $pdata['content']    = 'migration';
        $pdata['deletePath'] = $path;
        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Folder::exists($fullPath) )
        {
            $files = Folder::allFiles($fullPath, true);
        }

        $pdata['files'] = $files ?? [];

        Masterpage::pdata($pdata);
    }

    /**
     * Delete File
     */
    public function deleteFile()
    {
        $file = URI::get('deleteFile', 'all');

        if( File::exists($file) )
        {
            File::delete($file);
        }

        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    /**
     * Ajax Rename File
     */
    public function renameFile()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $old = Method::post('old');
        $new = Method::post('new');

        $controlOld = File::pathInfo($old, 'dirname');
        $controlNew = File::pathInfo($new, 'dirname');

        if( $controlOld === $controlNew )
        {
            File::rename($old, $new);
        }
    }

    /**
     * Ajax Save File
     */
    public function saveFile()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $link    = Method::post('link');
        $content = Method::post('content');

        File::write($link, Security::htmlDecode($content));
    }
}
