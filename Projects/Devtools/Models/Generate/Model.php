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
use URI;
use File;
use Redirect;
use Masterpage;
use Generate as Gen;
use ZN\Base;
use ZN\Model as ZNModel;

class Model extends ZNModel
{
    public static function sendMasterpageData()
    {
        $pdata['content']    = 'model';
        $pdata['deletePath'] = $path = 'Models';
        $pdata['fullPath']   = $fullPath = SELECT_PROJECT_DIR . $path;

        if( Folder::exists($fullPath) )
        {
            $files = Folder::allFiles($fullPath, true);
        }

        $pdata['files'] = $files ?? [];

        Masterpage::pdata($pdata);
    }

    public static function run()
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

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}