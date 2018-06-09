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

use ZN\Ability\Singleton;

class Lang
{
    use Singleton;

    /**
     * Keeps current language content
     * 
     * @var mixed
     */
    protected static $lang = NULL;

    /**
     * Keeps default configuration
     * 
     * @var mixed
     */
    protected static $default = false;

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = ucfirst($method);

        return self::select($method, ...$parameters);
    }

    /**
     * Default Language
     * 
     * @param mixed $class
     * 
     * @return self
     */
    public static function default($class)
    {
        self::$default = $class;
    
        return self::singleton();
    }

    /**
     * Get current lang code
     * 
     * @param void
     * 
     * @return mixed
     */
    public static function current()
    {
        if( ! Config::get('Services','uri')['lang'] )
        {
            return false;
        }
        else
        {
            return self::get();
        }
    }

    /**
     * Set current lang by uri
     */
    public static function setByURI()
    {  
        if( preg_match('/\/('.REQUESTED_CURRENT_PROJECT.'\/)*(?<lang>[a-z][a-z])($|\/)/', Base::currentPath(), $match) )
        {
            Lang::set($match['lang']);
        }
    }

    /**
     * Get language content
     * 
     * @param string $file    = NULL
     * @param string $str     = NULL
     * @param mixed  $changed = NULL
     * 
     * @return mixed
     */
    public static function select(String $file = NULL, String $str = NULL, $changed = NULL)
    {
        if( ! isset(self::$lang[$file]) )
        {      
            $file          = ($getLang = self::get()).'/'.Base::suffix($file, '.php');
            $langDir       = LANGUAGES_DIR . $file;
            $commonLangDir = EXTERNAL_LANGUAGES_DIR . $file;

            if( is_file($langDir) && ! IS::import($langDir) )
            {
                self::$lang[$file] = require $langDir;
            }
            elseif( is_file($commonLangDir) && ! IS::import($commonLangDir) )
            {
                self::$lang[$file] = require $commonLangDir;
            }
            elseif( ! empty(self::$default) && empty(self::$lang[$file]) )
            {
                self::$lang[$file] = self::getDefault()[$getLang] ?? false;
            }
        }

        if( empty($str) && isset(self::$lang[$file]) )
        {
            return self::$lang[$file];
        }
        elseif( ! empty(self::$lang[$file][$str]) )
        {
            $langstr = self::$lang[$file][$str];
        }
        else
        {
            return false;
        }

        if( ! is_array($changed) )
        {
            if( strstr($langstr, "%") && ! empty($changed) )
            {
                return str_replace("%", $changed , $langstr);
            }
            else
            {
                return $langstr;
            }
        }
        else
        {
            if( ! empty($changed) )
            {
                $values = [];

                foreach( $changed as $key => $value )
                {
                    $keys[]   = $key;
                    $values[] = $value;
                }

                return str_replace($keys, $values, $langstr);
            }
            else
            {
                return $langstr;
            }
        }
    }

    /**
     * Sets language
     * 
     * @param string $l = NULL
     * 
     * @return bool
     */
    public static function set(String $l = NULL) : Bool
    {
        if( empty($l) )
        {
            $l = Config::get('Project', 'language');
        }

        return $_SESSION[In::defaultProjectKey('SystemLanguageData')] = $l;
    }

    /**
     * Get language short code
     * 
     * @return string
     */
    public static function get() : String
    {
        $systemLanguageData        = In::defaultProjectKey('SystemLanguageData');
        $defaultSystemLanguageData = In::defaultProjectKey('DefaultSystemLanguageData');

        $default = Config::get('Project', 'language') ?: 'en';
        
        if( empty($_SESSION[$defaultSystemLanguageData]) )
        {
            $_SESSION[$defaultSystemLanguageData] = $default;
        }
        else
        {
            if( $_SESSION[$defaultSystemLanguageData] !== $default )
            {
                $_SESSION[$defaultSystemLanguageData] = $default;
                $_SESSION[$systemLanguageData]        = $default;

                return $default;
            }
        }

        if( empty($_SESSION[$systemLanguageData]) )
        {
            $_SESSION[$systemLanguageData] = $default;

            return $default;
        }
        else
        {
            return $_SESSION[$systemLanguageData];
        }
    }

    /**
     * Protected Get Default
     * 
     * @return mixed
     */
    protected static function getDefault()
    {
        $default = self::$default;

        self::$default = NULL;

        if( is_string($default) )
        {
            return get_class_vars($default);
        }
        elseif( is_object($default) )
        {
            return get_object_vars($default);
        }
        else
        {
            return false;
        }
    }
}
