<?php namespace ZN\Inclusion;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Style extends BootstrapExtends
{
    /**
     * Dynamic wizard script
     * 
     * @param string $script
     * 
     * @return string
     */
    public function wizard(String $script)
    {
        if( isset($script[0]) && $script[0] === '/' )
        {
            $script = CURRENT_CONTROLLER . $script;
        }

        $currentDirectory = pathinfo($script . '.php', PATHINFO_DIRNAME) . '/';
        $currentFile      = pathinfo($script . '.php', PATHINFO_FILENAME);

        $themeDirectory = defined('CURRENT_THEME_DIR') ? CURRENT_THEME_DIR : THEMES_DIR;

        if( ! is_dir($fullPath = $themeDirectory . $currentDirectory) )
        {
            mkdir($fullPath, 0755, true);
        }

        file_put_contents($path = $fullPath . \ZN\Filesystem::removeExtension($currentFile) . '.wizard.php', View::use($script, NULL, true));

        return self::tag($path);
    }
    
    /**
     * HTML Element
     * 
     * @param string $src = NULL
     * 
     * @return string
     */
    public static function tag(String $src = NULL) : String
    {
        return '<link href="'.$src.'" rel="stylesheet" type="text/css" />' . EOL;
    }

    /**
     * Get styles
     * 
     * @param string ...$styles
     * 
     * @return mixed
     */
    public static function use(...$styles)
    {
        return self::gettype('style', $styles);
    }
}
