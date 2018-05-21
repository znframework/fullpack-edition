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

use Http;
use Upload;
use Butcher;

class Integration extends Controller
{
    /**
     * Main
     */
    public function main()
    {
        Masterpage::page('theme-integration');
    }

    /**
     * Upload
     */
    public function upload()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }
        
        # Theme file is uploading.
        Upload::mimes('application/zip')->start('file', SELECT_PROJECT_DIR . 'Butchery');

        # If there is no error, the theme starts to integrate.
        if( ! Upload::error() )
        {
            Butcher::application(SELECT_PROJECT)->runDelete(SELECT_PROJECT);
        }   
    }
}
