<?php namespace Project\Controllers;

use Initialize as InitializeModel;

class Initialize extends Controller
{
    /**
     * Initialize Controller
     */
    public function main()
    {
        /**
         * Defines basic constants.
         */
        InitializeModel\Constants::basic();

        /**
         * Supported minimum version is checked.
         */
        InitializeModel\VersionControl::throw();

        /**
         * Get current version info.
         */
        View::znframework(InitializeModel\VersionControl::getLast());

        /**
         * Session is being checked for security.
         */
        InitializeModel\Login::control();

        /**
         * The necessary constants for Devtools are being called.
         */
        InitializeModel\Constants::get();
        
        /**
         * The database settings is configured according to the selected project.
         */
        InitializeModel\Database::config();

        /**
         * A list of menus and tools is being brought.
         */
        InitializeModel\Menus::defines();
    }
}
