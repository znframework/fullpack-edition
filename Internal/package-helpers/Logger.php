<?php namespace ZN\Helpers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Helper;

class Logger implements LoggerInterface
{
    /**
     * Notice log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function notice(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Emergency log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function emergency(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Alert log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function alert(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Error log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function error(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Warning log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function warning(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Critical log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function critical(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Info log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function info(string $message, string $time = NULL)
	{
		return 	self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Debug log
     * 
     * @param string $message
     * @param string $time
     * 
     * @return bool
     */
    public static function debug(string $message, string $time = NULL)
	{
		return self::report(__FUNCTION__, $message, NULL, $time);
	}

    /**
     * Report log
     * 
     * @param string $subject
     * @param string $message
     * @param string $destination
     * @param string $time
     * 
     * @return bool
     */
    public static function report(string $subject, string $message, string $destination = NULL, string $time = NULL) : bool
    {
        return Helper::report($subject, $message, $destination, $time);
    }
}
