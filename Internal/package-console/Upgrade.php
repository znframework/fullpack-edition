<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use File;
use Restful;
use ZN\ZN;
use ZN\Lang;

/**
 * @command upgrade
 * @description upgrade
 * 
 * @codeCoverageIgnore
 */
class Upgrade
{
    protected static $lang;

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {   
        self::$lang = Lang::default('ZN\Console\ConsoleDefaultlanguage')::select('Console');

        if( ZN::$projectType === 'FE' )
        {
            $return = self::fe();
        }
        else
        {
            $return = self::upgrade();
        }

        new Result($return);
    }

    /**
     * Protected upgrade FE
     */
    protected static function fe()
    {
        if( ! empty(ZN::upgrade()) )
        {
            $status = self::$lang['upgradeSuccess'];
        }
        else
        {
            $status = self::$lang['alreadyVersion'];

            if( $upgradeError = ZN::upgradeError() )
            {
                $status = $upgradeError; 
            }         
        }

        return $status;
    }

    /**
     * Proteted upgrade EIP
     */
    protected static function upgrade()
    {
        $open   = popen('composer update 2>&1', 'r');
        
        $result = fread($open, 2048);

        pclose($open);

        if( empty($result) )
        {
            return self::$lang['composerUpdate'];
        }
        
        return self::$lang['upgradeSuccess'];
    }
}