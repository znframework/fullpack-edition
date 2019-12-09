<?php namespace Home;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Validation;
use Method;
use Butcher;
use File;
use Folder;
use Session;
use Redirect;
use Masterpage;
use ZN\Model;

class Project extends Model
{
    public static function select($project)
    {
        Session::insert('project', $project);
    }

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

    public static function delete($project)
    {
        $path = PROJECTS_DIR . $project;

        if( Folder::exists($path) )
        {
            Session::delete('project');
            Folder::delete($path);
        }
    }

    public static function deleteBackup($backup)
    {
        $path = STORAGE_DIR . 'ProjectBackup' . DS . $backup;

        if( Folder::exists($path) )
        {
            Folder::delete($path);
        }
    }
}