<?php namespace ZN\ErrorHandling;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

/**
 * Default Configuration
 * 
 * Enabled when the configuration file can not be accessed.
 */
class ErrorHandlingDefaultConfiguration
{
    /*
    |--------------------------------------------------------------------------
    | Error Reporting
    |--------------------------------------------------------------------------
    |
    | Includes error reporting settings.
    |
    */

    public $errorReporting = E_ALL;

    /*
    |--------------------------------------------------------------------------
    | Escape Errors
    |--------------------------------------------------------------------------
    |
    | Error numbers for which the error indication is to be prevented.
    |
    */

    public $escapeErrors = [];

    /*
    |--------------------------------------------------------------------------
    | Exit Errors
    |--------------------------------------------------------------------------
    |
    | It is specified which error numbers will stop the code stream.
    |
    */

    public $exitErrors = [0, 2];
}
