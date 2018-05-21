<?php namespace Home;

use Validation;
use Method;
use Butcher;
use File;
use Redirect;
use ZN\Model;

class Project extends Model
{
    public static function create()
    {
        Validation::rules('project', ['alpha'], 'Project Name');

        if( ! $error = Validation::error('string') )
        {
            if( $selectButcherTheme = Method::post('selectButcherTheme') )
            {
                $extractType = Method::post('extractType') ?: 'extract';

                Butcher::$extractType($selectButcherTheme, Method::post('project'));
            }  
            else
            {
                $source = EXTERNAL_FILES_DIR . 'DefaultProject.zip';
                $target = PROJECTS_DIR . Method::post('project');

                File::zipExtract($source, $target);
            }
            
            Redirect::location('', 0, ['success' => LANG['success']]);
        }
        else
        {
            Masterpage::error($error);
        }
    }
}