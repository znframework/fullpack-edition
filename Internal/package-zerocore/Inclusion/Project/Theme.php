<?php namespace ZN\Inclusion\Project;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\IS;
use ZN\Base;
use ZN\Request;

class Theme
{
    /**
     * Active status
     * 
     * @var string
     */
    public static $active = NULL;

    /**
     * Match elements.
     * 
     * @var array
     */
    protected static $elements = [NULL, NULL];

    /**
     * Active theme.
     * 
     * @param string $active = 'Default'
     * 
     * @return void
     */
    public static function active(String $active = 'Default')
    {
        self::$active = Base::suffix($active);
    }

    /**
     * Match element.
     * 
     * @param array $elements
     */
    public static function matchElement(String $inputs = NULL, String $attributes = NULL)
    {
        self::$elements = [Base::prefix($inputs, '|'), Base::prefix($attributes, '|')];

        return new self;
    }

    /**
     * Theme integration.
     * 
     * 5.7.5.5[changed]
     * 
     * @param string $themeName
     * @param string &$data
     * 
     * @return void
     */
    public static function integration(String $themeName, String &$data)
    {
        $data = preg_replace_callback
        (
            [
                '/<(link|img|script|div|a' . self::$elements[0] . ')\s.*?(href|src' . self::$elements[1] . ')\=(\"|\')(?<element>.*?)(\"|\').*?\>/i',
                '/background(-image)*\s*\:\s*url\((?<element>.*?)\)/i'                            
            ], 
            function($selector) use ($themeName)
            {
                $orig = $selector[0];
                $path = trim($selector['element'], '\'');
                $path = preg_replace('/(\.\.\/)+/', '//', $path);

                if( ! IS::url($path) && ! is_file($path) )
                {
                    $suffix = Base::suffix($themeName) . $path;

                    if( is_file(THEMES_DIR . $suffix) )
                    {
                        return self::getReplacePath($path, THEMES_DIR, $suffix, $orig);
                    }
                    elseif( is_file(EXTERNAL_THEMES_DIR . $suffix) )
                    {
                        return self::getReplacePath($path, EXTERNAL_THEMES_DIR, $suffix, $orig);
                    }
                }     

                return $selector[0];
                
            }, $data
        );
    }

    /**
     * protected get replace path
     */
    protected static function getReplacePath($path, $dir, $suffix, $orig)
    {
        return str_replace($path, Request::getBaseURL($dir) . $suffix, $orig);
    }
}
