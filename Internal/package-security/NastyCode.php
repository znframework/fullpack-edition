<?php namespace ZN\Security;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Singleton;

class NastyCode
{
    /**
     * Encode Nasty Code
     * 
     * @param string $string
     * @param mixed  $badWords   = NULL
     * @param mixed  $changeChar = '[badchars]'
     * 
     * @return string
     */
    public static function encode(string $string, $badWords = NULL, $changeChar = '[badchars]') : string
    {
        if( empty($badWords) )
        {
            $secnc      = Properties::$ncEncode;
            $badWords   = $secnc['badChars'];
            $changeChar = $secnc['changeBadChars'];
        }

        $regex = Singleton::class('ZN\Regex');

        if( ! is_array($badWords) )
        {
            return $string = $regex->replace($badWords, $changeChar, $string, 'xi');
        }

        $ch = '';
        $i  = 0;

        foreach( $badWords as $value )
        {
            if( ! is_array($changeChar) )
            {
                $ch = $changeChar;
            }
            else
            {
                if( isset($changeChar[$i]) )
                {
                    $ch = $changeChar[$i];
                    $i++;
                }
            }

            $string = $regex->replace($value, $ch, $string, 'xi');
        }

        return $string;
    }
}
