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
use Migration as ZNMigration;
use Redirect;
use Masterpage;
use ZN\Base;
use ZN\Model;

class Migration extends Model
{
    public static function sendMasterpageData()
    {
        $pdata['content']    = 'migration';
        $pdata['deletePath'] = $path = 'Models'. DS .'Migrations';
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
        Validation::rules('migration', ['required', 'alnum'], LANG['migrationName']);

        if( ! $error = Validation::error('string') )
        {
            $path = PROJECTS_DIR . SELECT_PROJECT . DS . 'Models/Migrations/';

            ZNMigration::path($path)->create(Method::post('migration'), (int) Method::post('version'));

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}