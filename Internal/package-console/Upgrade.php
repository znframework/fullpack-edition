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
        elseif( ZN::$projectType === 'EIP' )
        {
            $return = self::eip();
        }
        elseif( ZN::$projectType === 'SE' )
        {
            $return = self::eip('single-edition');
        }
        else
        {
            $return = self::eip('custom-edition');
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
    protected static function eip($tag = 'znframework')
    {
        if( $return = Restful::useragent(true)->get('https://api.github.com/repos/znframework/'.$tag.'/tags') )
        {
            if( ! isset($return->message) )
            {
                usort($return, function($data1, $data2){ return strcmp($data1->name, $data2->name); });

                rsort($return);

                $lastest = $return[0];

                $lastVersionData = $lastest->name ?? ZN_VERSION;

                $open   = popen('composer update 2>&1', 'r');
                $result = fread($open, 2048);
                pclose($open);

                if( empty($result) )
                {
                    $status = self::$lang['composerUpdate'];
                }
                else
                {
                    if( ZN_VERSION !== $lastVersionData )
                    {                   
                        File::replace(DIRECTORY_INDEX, ZN_VERSION, $lastVersionData);
                    
                        $status = self::$lang['upgradeSuccess'];
                    }
                    else
                    {
                        $status = self::$lang['alreadyVersion'];
                    }
                }
            }
            else
            {
                $status = $return->message;
            }

            return $status;
        }
    }
}