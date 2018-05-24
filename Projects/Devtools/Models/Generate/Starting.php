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

class Starting extends Model
{
    public static function sendMasterpageData()
    {
        $pdata['content'] = 'starting';
        $pdata['deletePath'] = $path = 'Starting';
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
        Validation::rules('file', ['required'], LANG['fileName']);

        if( ! $error = Validation::error('string') )
        {
            $viewName = Method::post('file');

            $path = Method::post('type') . DS;

            $path = 'Starting' . DS . $path;

            $viewPath = SELECT_PROJECT_DIR . $path . Base::suffix($viewName, '.php');

            if( ! File::exists($viewPath) )
            {
                File::write($viewPath, '<?php');
            }

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}