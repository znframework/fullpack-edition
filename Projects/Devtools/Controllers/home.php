<?php namespace Project\Controllers;

use Import;
use Restful;
use Method;
use Validation;
use File;
use Folder;
use Session;
use Cookie;
use Json;
use URI;
use Security;
use Http;
use Redirect;
use Lang;
use URL;
use Butcher;
use ZN\Base;
use Home as HomeModel;

class Home extends Controller
{
    /**
     * Main Page
     */
    public function main(String $params = NULL)
    {
        /**
         * Creates a new project on request.
         */
        if( Method::post('create') )
        {
            HomeModel\Project::create();
        }
        
        /**
         * It brings some stats data from the ZN Framework via API.
         */
        HomeModel\Statistics::get();

        /**
         * .zip allows you to extract the component theme files.
         */
        HomeModel\Themes::extract();

        /**
         * Gets a list of existing themes.
         */
        View::butcherThemes(HomeModel\Themes::get());
        
        /**
         * The corresponding view is being installed.
         */
        Masterpage::page('dashboard');

        /**
         * Sending data to Masterpage.
         */
        Masterpage::pdata(['return' => $return]);
    }

    /**
     * Docs Page
     */
    public function docs(String $params = NULL)
    {
        $docs = FILES_DIR . 'docs.json';

        $return = [];

        if( Method::post('refresh') || ! file_exists($docs) )
        {
            if( $return = Restful::get('https://api.znframework.com/docs') )
            {
                File::write($docs, Json::encode($return));
            }
            else
            {
                Masterpage::error(LANG['docsRetrievalFailed']);
            }
        }
        else
        {
            $return = Json::decode(File::read($docs));
        }

        Import::handload('Functions');

        Masterpage::plugin(['name' => 
        [
            'Dashboard/highlight/styles/agate.css',
            'Dashboard/highlight/highlight.pack.js'
        ]]);

        Masterpage::pdata(['docs' => $return]);
      
        Masterpage::page('docs');
    }

    /**
     * Delete
     */
    public function delete($project = NULL)
    {
        if( ! empty($project) )
        {
            $path = PROJECTS_DIR . $project;

            if( Folder::exists($path) )
            {
                Session::delete('project');
                Folder::delete($path);
            }
        }

        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    /**
     * Delete Backup
     */
    public function deleteBackup($backup = NULL)
    {
        $path = $path = STORAGE_DIR . 'ProjectBackup' . DS . $backup;

        if( Folder::exists($path) )
        {
            Folder::delete($path);
        }

        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    /**
     * Lang
     */
    public function lang($lang = NULL)
    {
        Lang::set($lang);

        Redirect::location((string) URL::prev());
    }

    /**
     * Project
     */
    public function project($project = NULL)
    {
        Session::insert('project', $project);
        Redirect::location((string) URL::prev());
    }

    /**
     * Editor Theme
     */
    public function editorTheme($theme = NULL)
    {
        Cookie::insert('editorTheme', $theme);
        Redirect::location((string) URL::prev());
    }
}
