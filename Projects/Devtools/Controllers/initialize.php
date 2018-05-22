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

use Initialize as InitializeModel;

class Initialize extends Controller
{
    /**
     * Initialize Controller
     */
    public function main()
    {
        # Sets max execution time 0
        InitializeModel\Ini::maxExecutionTime();

        # Defines basic constants.
        InitializeModel\Constants::basic();

        # Supported minimum version is checked.
        InitializeModel\VersionControl::throw();

        # Get current version info.
        View::znframework(InitializeModel\VersionControl::getLast());

        # Session is being checked for security.
        InitializeModel\Login::control();

        # The necessary constants for Devtools are being called.
        InitializeModel\Constants::get();
        
        # The database settings is configured according to the selected project.
        InitializeModel\Database::config();

        # A list of menus and tools is being brought.
        InitializeModel\Menus::defines();
    }
}
