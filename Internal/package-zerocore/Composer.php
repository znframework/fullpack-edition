<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Composer
{   
    /**
     * Default vendor path
     * 
     * @var string
     */
    protected static $path = 'vendor/autoload.php';

    /**
     * Protected Composer Loader
     * 
     * @param mixed $composer
     * 
     * @return void
     */
    public static function loader($composer)
    {
        # Loads the default path if the parameter is set to true.
        # Default path vendor/autoloader.php
        if( $composer === true )
        {
            self::requireVendorAutoloadFile();
        }
        # Loads the parameter if it specifies a valid file path.
        elseif( is_file($composer) )
        {
            require $composer;
        }
        # The exception is throw when the parameter contains an invalid path.
        else
        {
            self::invalidComposerPathReport($composer);
        }
    }

    /**
     * Protected require vendor autoload file
     */
    protected static function requireVendorAutoloadFile()
    {
        if( is_file(self::$path) )
        {
            require self::$path;
        }
    }

    /**
     * Protected invalid composer path report
     */
    protected static function invalidComposerPathReport($composer)
    {
        $path = Base::suffix($composer) . self::$path;

        Helper::report('Error', Lang::default('ZN\CoreDefaultLanguage')::select('Error', 'fileNotFound', $path) ,'AutoloadComposer');

        throw new Exception('Error', 'fileNotFound', $path);
    }
}