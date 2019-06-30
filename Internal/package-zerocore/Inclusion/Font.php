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


use ZN\Base;
use ZN\Config;
use ZN\Request;
use ZN\Filesystem;
use ZN\Inclusion\Project\Theme;

class Font extends BootstrapExtends
{
    /**
     * Get Fonts
     * 
     * @param string ...$fonts
     * 
     * @return mixed
     */
    public static function use(...$fonts)
    {
        $eol       = EOL;
        $str       = "<style type='text/css'>".$eol;
        $args      = self::_parameters($fonts, 'fonts');
        $lastParam = $args->lastParam;
        $arguments = $args->arguments;
        $links     = $args->cdnLinks;
        $strEx     = NULL;

        foreach( $arguments as $font )
        {
            if( is_array($font) )
            {
                $font = '';
            }

            $f = self::_fontName($font);

            $allFontExtensions = array_merge(Config::expressions('differentFontExtensions'), ['svg', 'woff', 'otf', 'ttf', 'eot']);

            if( ! empty($allFontExtensions) )
            {
                foreach( $allFontExtensions as $of )
                {  
                    $fontFile   = THEMES_DIR . Theme::$active . $font;  
                    $baseUrl    = Request::getBaseURL($fontFile);
                    $isFontFile = $fontFile . ( $fontExtension = Base::prefix($of, '.') );

                    if( ! is_file($isFontFile) )
                    {
                        $fontFile   = EXTERNAL_THEMES_DIR . Theme::$active . $font;
                        $baseUrl    = Request::getBaseURL($fontFile);
                        $isFontFile = $fontFile . $fontExtension;
                    }

                    if( is_file($isFontFile) )
                    {
                        if( $of === 'eot' )
                        {
                            $str .= '<!--[if IE]>' . $eol;
                            $str .= self::_face($f, $baseUrl, 'eot');
                            $str .= '<![endif]-->';
                            $str .= $eol;
                        }
                        else
                        {
                            $str .= self::_face($f, $baseUrl . $fontExtension);
                        }
                    }
                }
            }

            $cndFont = isset($links[strtolower($font)]) ? $links[strtolower($font)] : NULL;

            if( ! empty($cndFont) ) $str .= self::_face(self::_fontName($cndFont), $cndFont);
        }

        $str .= '</style>'.$eol;

        if( ! empty($str) )
        {
            if( $lastParam === true )
            {
                return $str;
            }
            else
            {
                echo $str;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Protected Font Name
     */
    protected static function _fontName($font)
    {
        $divide = explode('/', $font);

        $sub  = NULL;
        $name = $divide[0];

        if( $sub = ($divide[1] ?? NULL) )
        {
            if( $name === $sub )
            {
                $sub = NULL;
            }
        }

        return $name . $sub;
    }

    /**
     * Protected Font Face
     */
    protected static function _face($f, $baseUrl, $extension = NULL)
    {
        $base = $baseUrl;

        if( $extension !== NULL )
        {
            $base = Base::suffix($baseUrl, '.' . $extension);
        }

        return '@font-face{font-family:"' . Filesystem::removeExtension($f) . '"; src:url("' . $base . '") format("truetype")}' . EOL;
    }
}
