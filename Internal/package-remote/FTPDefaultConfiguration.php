<?php namespace ZN\Remote;
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
class FTPDefaultConfiguration
{
    /*
    |--------------------------------------------------------------------------
    | FTP
    |--------------------------------------------------------------------------
    |
    | Includes FTP connection settings.
    |
    */
   
    public $host       = '';
    public $user       = '';  
    public $password   = '';   
    public $timeout    = 90; 
    public $port       = 21;
    public $sslConnect = false;
}
