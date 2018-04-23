<?php namespace ZN\Validation;
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
use ZN\Security;
use ZN\Singleton;

class Validator implements ValidatorInterface
{
    /**
     * Is valid card
     * 
     * @param string $data
     * @param string $type = NULL
     * 
     * @return bool
     */
    public static function card(String $data, String $type = NULL) : Bool
    {
        return CreditCard\Validator::card($data, $type);
    }

    /**
     * Is valid cvc
     * 
     * @param int    $cvc
     * @param string $type
     */
    public static function cvc(Int $cvc, String $type = NULL) : Bool
    {
        return CreditCard\Validator::cvc($cvc, $type);
    }

    /**
     * Is valid date
     * 
     * @param string $year
     * @param string $month
     * 
     * @return bool
     */
    public static function cardDate(String $date) : Bool
    {
        $dateEx = explode('/', $date);

        if( ! isset($dateEx[1]) )
        {
            return false;
        }

        return CreditCard\Validator::date($dateEx[1], $dateEx[0]);
    }

    /**
     * Trim data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function trim(String $data) : String
    {
        return trim($data);
    }

    /**
     * Nasty code clean.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function nc(String $data) : String
    {
        $secnc = Security\Properties::$ncEncode;

        return Security\NastyCode::encode($data, $secnc['badChars'], $secnc['changeBadChars']);
    }

    /**
     * Encode html tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function html(String $data) : String
    {
        return Security\Html::encode($data);
    }

    /**
     * Encode cross site scripting.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function xss(String $data) : String
    {
        return Security\CrossSiteScripting::encode($data);
    }

    /**
     * Encode injection data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function injection(String $data) : String
    {
        return Security\Injection::encode($data);
    }

    /**
     * Encode script tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function script(String $data) : String
    {
        return Security\Script::encode($data);
    }

    /**
     * Encode PHP tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function php(String $data) : String
    {
        return Security\PHP::encode($data);
    }

    /**
     * Empty control.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function required(String $data) : Bool
    {
        return ! empty($data);
    }

    /**
     * Control captcha code.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function captcha(String $data) : Bool
    {
        return $data === Singleton::class('ZN\Captcha\Render')->getCode();
    }

    /**
     * Match password.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function matchPassword(String $data, String $check) : Bool
    {
        return self::match($data, $check);
    }

    /**
     * Match.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function match(String $data, String $check) : Bool
    {
        return $data === $check;
    }

    /**
     * Pattern.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function pattern(String $data, String $check) : Bool
    {
        return preg_match($check, $data);
    }

    /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function between(Float $value, Float $min, Float $max) : Bool
    {
        return self::betweenBoth($value, $min, $max, 'noboth');
    }

    /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function betweenBoth(Float $value, Float $min, Float $max, $type = 'both') : Bool
    {
        if( $min > $max )
        {
            $mmin = $min;
            $min  = $max;
            $max  = $mmin;
        }

        if( $type === 'both' )
        {
            return $value >= $min && $value <= $max;
        }

        return $value > $min && $value < $max;
    }

    /**
     * Checks whether the donation has phone information.
     * 
     * @param string $data
     * @param string $desing = NULL
     * 
     * @return bool
     */
    public static function phone(String $data, String $pattern = NULL) : Bool
    {
        if( $pattern !== NULL)
        {
            $phoneData = preg_replace('/([^\*])/', 'key:$1', $pattern);
            $phoneData = '/^'.str_replace(['*', 'key:'], ['[0-9]', '\\'], $phoneData).'$/';
        }
        else
        {
            $phoneData = '/\+*[0-9]{10,14}$/';
        }

        return (bool) preg_match($phoneData, $data);
    }

    /**
     * The data should be numeric.
     * 
     * @param mixed $data
     * 
     * @return bool
     */
    public static function numeric($data) : Bool
    {
        return is_numeric($data);
    }

    /**
     * Checks whether the verb is alphabetic.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alnum(String $data) : Bool
    {
        return (bool) preg_match('/^\w+$/', $data);
    }

    /**
     * Controls whether the verb is alphanumeric.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alpha(String $data) : Bool
    {
        return ctype_alpha($data);
    }

    /**
     * The citizenship identification number checks.
     * 
     * @param string $no 
     * 
     * @return bool
     */
    public static function identity($no) : Bool
    {
        if( ! is_numeric($no) || strlen($no) !== 11  )
        {
            return false;
        }

        $no = (string) $no;

        $numone     = ($no[0] + $no[2] + $no[4] + $no[6]  + $no[8]) * 7;
        $numtwo     = $no[1] + $no[3] + $no[5] + $no[7];
        $result     = $numone - $numtwo;
        $tenth      = $result % 10;
        $total      = ($no[0] + $no[1] + $no[2] + $no[3] + $no[4] + $no[5] + $no[6] + $no[7] + $no[8] + $no[9]);
        $elewenth   = $total % 10;

        if( $no[0] == 0 )
        {
            return false;
        }
        elseif( $no[9] != $tenth )
        {
            return false;
        }
        elseif( $no[10] != $elewenth )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks whether the email is an e-mail.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function email(String $data) : Bool
    {
        return IS::email($data);
    }

    /**
     * Checks whether the data is url.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function url(String $data) : Bool
    {
        return IS::url($data);
    }

    /**
     * Checks whether the data is special char.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function specialChar(String $data) : Bool
    {
        return (bool) preg_match('/[\W]+/', $data);
    }

    /**
     * Makes the maximum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function maxchar(String $data, Int $char) : Bool
    {
        return ( strlen($data) <= $char );
    }

    /**
     * Makes the minimum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function minchar(String $data, Int $char) : Bool
    {
        return ( strlen($data) >= $char );
    }
}
