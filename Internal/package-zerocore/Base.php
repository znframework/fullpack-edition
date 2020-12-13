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

class Base
{   
    /**
     * Is resource or object
     * 
     * @param resource|object $object;
     * 
     * @return bool
     */
    public static function isResourceObject($object)
    {
        if( IS::phpVersion('8') )
        {
            return is_object($object);
        }
        else
        {
            return is_resource($object);
        } 
    }
    
    /**
     * Get default project or host name.
     *
     * @param string $default = 'Frontend'
     * 
     * @return string
     */
    public static function project($default = 'Frontend')
    {
        $host = self::host();

        return ! empty($host) && is_dir(PROJECTS_DIR . $host) ? $host : $default;
    }


    /**
     * Get path info
     * 
     * @return string|false
     */
    public static function currentPath()
    {
        return $_SERVER['PATH_INFO'] ?? $_SERVER['QUERY_STRING'] ?? false;
    }

    /**
     * illustrate
     * 
     * Returns the constant value. If the constant is undefined, 
     * it defines the constant according to 
     * the specified value and returns the value.
     * 
     * @param string $const
     * @param mixed  $value = ''
     *
     * @return mixed
     */
    public static function illustrate(String $const, $value = '')
    {
        if( ! defined($const) )
        {
            define($const, $value);
        }
        else
        {
            if( $value !== '' )
            {
                return $value;
            }
        }

        return constant($const);
    }

    /**
     * layer
     * 
     * Loads the layer files.
     * 
     * @param string $layer
     * 
     * @return void
     * 
     */
    public static function layer(String $layer)
    {
        $path = $layer . '.php';

        if( is_file($require = (LAYERS_DIR . $path)) )
        {
            self::import($require);
        }

        if( is_file($require = (EXTERNAL_LAYERS_DIR . $path)) )
        {
            self::import($require);
        }
    }

    /**
     * import
     * 
     * Include files once. Performance is better than require_once function.
     * 
     * @param string $file
     * 
     * @return mixed
     */
    public static function import(String $file)
    {
        $constant = 'ImportFilePrefix' . $file;

        if( ! defined($constant) )
        {
            define($constant, true);

            if( is_file($file = PROJECT_TYPE === 'SE' ? REAL_BASE_DIR . $file : $file) )
            {
                return require $file;
            }

            return false;
        }
    }

    /**
     * host
     * 
     * Returns the system host information.
     * 
     * @param void
     * 
     * @return string
     */
    public static function host() : String
    {
        if( isset($_SERVER['HTTP_X_FORWARDED_HOST']) )
        {
            $host     = $_SERVER['HTTP_X_FORWARDED_HOST'];
            $elements = explode(',', $host);
            $host     = trim(end($elements));
        }
        else
        {
            $host = $_SERVER['HTTP_HOST']   ??
                    $_SERVER['SERVER_NAME'] ??
                    $_SERVER['SERVER_ADDR'] ??
                    '';
        }

        $host = trim($host);

        if( defined('IS_MAIN_DOMAIN') )
        {
            $host = self::prefix($host, 'www.');
        }

        return $host;
    }

    /**
     * Removes an expression in begin of a string.,
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removePrefix(String $data = NULL, String $fix = '/') : String
    {
        if( strpos($data, $fix) === 0 ) 
        {
            $data = substr($data, strlen($fix));
        } 

        return $data;
    }

    /**
     * Removes an expression in begin of a string.,
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removeSuffix(String $data = NULL, String $fix = '/') : String
    {
        if( strrpos($data, $fix) === ($start = strlen($data) - strlen($fix)) ) 
        {
            $data = substr($data, 0, $start);
        } 

        return $data;
    }

    /**
     * It removes an expression from both sides of a string.
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removePresuffix(String $data = NULL, String $fix = '/') : String
    {
        return self::removeSuffix(self::removePrefix($data, $fix), $fix);
    }

    /**
     * suffix 
     * 
     * It is used to append a suffix to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function suffix(String $string = NULL, String $fix = '/') : String
    {
        return self::prefix($string, $fix, __FUNCTION__);
    }

    /**
     * prefix 
     * 
     * It is used to append a prefix to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function prefix(String $string = NULL, String $fix = '/', $type = __FUNCTION__) : String
    {
        $stringFix = $type === 'prefix' ? $fix . $string : $string . $fix;

        if( strlen($fix) <= strlen($string) )
        {
            $prefix = $type === 'prefix' ? substr($string, 0, strlen($fix)) : substr($string, -strlen($fix));

            if( $prefix !== $fix )
            {
                $string = $stringFix;
            }
        }
        else
        {
            $string = $stringFix;
        }

        if( $string === $fix )
        {
            return false;
        }

        return $string;
    }

    /**
     * prefix 
     * 
     * Used to append both suffixes and prefixes to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function presuffix(String $string = NULL, String $fix = '/') : String
    {
        return self::suffix(self::prefix(empty($string) ? $fix . $string . $fix : $string, $fix), $fix);
    }

    /**
     * headers
     * 
     * Send HTTP headers in singular or plural structure.
     * 
     * @param mixed $header
     * 
     * @return void
     */
    public static function headers($header)
    {
        if( ! is_array($header) )
        {
            header($header);
        }
        else
        {
            if( ! empty($header) ) foreach( $header as $k => $v )
            {
                header($v);
            }
        }
    }

    /**
     * trace
     * 
     * Produces formatted output that terminates the operation.
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function trace(String $message)
    {
        # Shows console trace
        if( defined('CONSOLE_ENABLED') )
        {
            self::consoleTrace('CONSOLE TRACE', $message);
        }

        $style  = 'border:solid 1px #E1E4E5;';
        $style .= 'background:#FEFEFE;';
        $style .= 'padding:10px;';
        $style .= 'margin-bottom:10px;';
        $style .= 'font-family:Calibri, Ebrima, Century Gothic, Consolas, Courier New, Courier, monospace, Tahoma, Arial;';
        $style .= 'color:#666;';
        $style .= 'text-align:left;';
        $style .= 'font-size:14px;';

        $message = preg_replace('/\[(.*?)\]/', '<span style="color:#990000;">$1</span>', $message);

        $str  = "<div style=\"$style\">";
        $str .= $message;
        $str .= '</div>';

        exit($str);
    }

    /**
     * Console trace
     * 
     * @param string $title
     * @param string $message
     */
    public static function consoleTrace(String $title, String $message)
    {
        $repeat = self::presuffix(str_repeat('-', strlen($message) + 2), '+');
        $titleSpaceRepeat = str_repeat(' ', strlen($message) - strlen($title));

        $output  = $repeat . CRLF;
        $output .= '| '.$title . $titleSpaceRepeat .' |' . CRLF;
        $output .= $repeat . CRLF;
        $output .= '| ' . $message . ' |' . CRLF;
        $output .= $repeat;

        exit($output);
    }
}