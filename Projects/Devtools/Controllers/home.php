<?php namespace Project\Controllers;

use Import;
use Method;
use Redirect;
use Lang;
use URL;
use Home as HomeModel;

class Home extends Controller
{
    /**
     * Main Page
     */
    public function main()
    {
        # Creates a new project on request.
        if( Method::post('create') )
        {
            HomeModel\Project::create();
        }
        
        # It brings some stats data from the ZN Framework via API.
        HomeModel\Statistics::get();

        # .zip allows you to extract the component theme files.
        HomeModel\Themes::extract();

        # Gets a list of existing themes.
        View::butcherThemes(HomeModel\Themes::get());
        
        # Sending data to Masterpage.
        Masterpage::pdata(['return' => $return]);

        # The corresponding view is being installed.
        Masterpage::page('dashboard');
    }

    /**
     * Docs Page
     */
    public function docs(String $params = NULL)
    {
        # User defined functions are included.
        Import::handload('Functions');

        # Custom plugins are loaded on the page.
        Masterpage::plugin(['name' => 
        [
            'Dashboard/highlight/styles/agate.css',
            'Dashboard/highlight/highlight.pack.js'
        ]]);

        # Sending data to Masterpage.
        Masterpage::pdata(['docs' => HomeModel\Docs::get()]);
        
        # The corresponding view is being installed.
        Masterpage::page('docs');
    }

    /**
     * Delete
     */
    public function delete($project = NULL)
    {
        # The project name will be deleted if it is not empty.
        if( ! empty($project) )
        {
            HomeModel\Project::delete($project);
        }

        # Backward redirect is done.
        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    /**
     * Delete Backup
     */
    public function deleteBackup($backup = NULL)
    {  
        # The backup name will be deleted if it is not empty.
        if( ! empty($backup) )
        {
            HomeModel\Project::deleteBackup($backup);
        }

        # Backward redirect is done.
        Redirect::location((string) URL::prev(), 0, ['success' => LANG['success']]);
    }

    /**
     * Lang
     */
    public function lang($lang = NULL)
    {
        # The selected language is changing.
        Lang::set($lang);

        # Backward redirect is done.
        Redirect::location((string) URL::prev());
    }

    /**
     * Project
     */
    public function project($project = NULL)
    {
        # Select project.
        HomeModel\Project::select($project);

        # Backward redirect is done.
        Redirect::location((string) URL::prev());
    }

    /**
     * Editor Theme
     */
    public function editorTheme($theme = NULL)
    {
        # Selects editor
        HomeModel\Themes::selectEditor($theme);
        
        # Backward redirect is done.
        Redirect::location((string) URL::prev());
    }
}
