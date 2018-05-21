<?php namespace Project\Controllers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Post;
use Redirect;
use Cronjobs as CronjobsModel;

class Cronjobs extends Controller
{
    /**
     * Magic Constructor
     */
    public function __construct()
    {   
        # The parent constructor is being called.
        parent::__construct();

        # The scheduled task is determined for which project.
        CronjobsModel\Job::selectProject();
    }

    /**
     * Main
     */
    public function main(String $params = NULL)
    {
        # Crontab can only be used with unix operating systems.
        if( PHP_OS !== 'Linux' && PHP_OS !== 'Unix' )
        {
            return Masterpage::error(LANG['availableLinux']);
        }

        # Scheduled task creation.
        if( Post::create() )
        {
            CronjobsModel\Job::create();
        }

        # Sending data to Masterpage.
        Masterpage::pdata(['list' => CronjobsModel\Job::list()]);

        # The corresponding view is being loaded.
        Masterpage::page('cronjob');
    }

    /**
     * Delete
     * 
     * @param int $id
     */
    public function delete(Int $id)
    {
        # Scheduled task deletion.
        CronjobsModel\Job::delete($id);

        # Backward redirect is done.
        Redirect::location('cronjobs');
    }
}
