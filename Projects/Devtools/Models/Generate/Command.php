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

use Folder;
use Validation;
use Method;
use Arrays;
use URI;
use Redirect;
use Masterpage;
use Generate as Gen;
use ZN\Model;

class Command extends Model
{
    public static function sendMasterpageData()
    {
        Masterpage::pdata
        ([
            'content'    => 'command',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . ($path = 'Commands')),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    public static function run()
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

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}