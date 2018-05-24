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
use Arrays;
use Validation;
use Method;
use URI;
use File;
use Redirect;
use Masterpage;
use ZN\Base;
use ZN\Model as ZNModel;

class View extends ZNModel
{
    public static function defineTemplates()
    {
        $templates = Folder::files(TEMPLATES_DIR, 'php');
        $templates = Arrays::combine($templates, $templates);
        $templates['none'] = 'none';

        define('VIEW_TEMPLATES', $templates);
    }

    public static function sendMasterpageData()
    {
        $pdata['content']    = 'view';
        $pdata['deletePath'] = $path = 'Views';
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

            Redirect::location(URI::active(), 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}