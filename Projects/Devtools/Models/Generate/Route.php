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
use ZN\Base;
use ZN\Model;

class Route extends Model
{
    public static function sendMasterpageData()
    {
        $pdata['content']    = 'route';
        $pdata['deletePath'] = ($path = 'Routes');
        $pdata['files']      = Folder::allFiles($pdata['fullPath'] = SELECT_PROJECT_DIR . $path, true);

        Masterpage::pdata($pdata);
    }

    public static function run()
    {
        Validation::rules('route', ['required', 'alnum'], LANG['routeName']);

        if( ! $error = Validation::error('string') )
        {
            $functions = explode(',', Method::post('functions'));

            $routePath = SELECT_PROJECT_DIR . 'Routes' . Base::suffix(Base::prefix(Method::post('route')), '.php');

            if( ! File::exists($routePath) )
            {
                File::create($routePath);
            }

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}