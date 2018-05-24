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

class Library extends Model
{
    public static function sendMasterpageData()
    {
        Masterpage::pdata
        ([
            'content'    => 'library',
            'title'      => 'libraries',
            'fullPath'   => ($fullPath = SELECT_PROJECT_DIR . ($path = 'Libraries')),
            'deletePath' => $path,
            'files'      => Folder::allFiles($fullPath, true)
        ]);
    }

    public static function run()
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

                Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
            }
            else
            {
                Masterpage::error($error);
            }
    }
}