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

class Robots
{   
    /**
     * Robots file
     * 
     * @var string
     */
    protected static $file = 'robots.txt';

    /**
     * Creates robots.txt
     */
    public static function create()
    {
        $rules  = Config::get('Robots', 'rules');
        $robots = '';

        if( IS::array($rules) ) foreach( $rules as $key => $val )
        {
            # Single usage
            if( ! is_numeric($key) )
            {
                self::createFileContent($key, $val, $robots);
            }
            # Multi usage
            else
            {
                if( IS::array($val) ) foreach( $val as $r => $v ) 
                {
                    self::createFileContent($r, $v, $robots);
                }
            }
        }

        # If the content to be written is the same, rewriting is not performed.
        if( trim($robots) === self::getContent() )
        {
            return false;
        }

        # The contents of the robot file are created.
        return (bool) self::putContent($robots);
    }

    /**
     * Protected get file path
     */
    protected static function getFilePath()
    {
        $file = self::$file;

        if( defined('ZN_SHARED_DIR') )
        {
            $file = ZN_SHARED_DIR . $file; // @codeCoverageIgnore
        }

        return $file;
    }

    /**
     * Protected put robots content
     */
    protected static function putContent($robots)
    {
        return file_put_contents(self::getFilePath(), trim($robots));
    }

    /**
     * Protected get robots content
     */
    protected static function getContent()
    {
        if( is_file($file = self::getFilePath()) )
        {
           return trim(file_get_contents($file));
        }
        
        return '';
    }

    /**
     * Protected create robots file content
     */
    protected static function createFileContent($key, $val, &$robots)
    {
        switch( $key )
        {
            case 'userAgent':
                $robots .= ! empty( $val ) ? 'User-agent: ' . $val . EOL : '';
            break;

            case 'allow'    :
            case 'disallow' :
                if( ! empty($val) ) foreach( $val as $v )
                {
                    $robots .= ucfirst($key) . ': ' . $v . EOL;
                }
            break;
        }
    }
}