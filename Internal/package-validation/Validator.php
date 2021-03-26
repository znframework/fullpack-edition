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
use ZN\DateTime\Date;

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
    public static function card(string $data, string $type = NULL) : bool
    {
        return CreditCard\Validator::card($data, $type);
    }

    /**
     * Is valid cvc
     * 
     * @param int    $cvc
     * @param string $type
     */
    public static function cvc(int $cvc, string $type = NULL) : bool
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
    public static function cardDate(string $date) : bool
    {
        $dateEx = explode('/', $date);

        if( ! isset($dateEx[1]) )
        {
            return false;
        }

        $date   = (new Date)->convert($dateEx[1] . '-' . $dateEx[0] . '-01', '{y}/{mn0}');

        $dateEx = explode('/', $date);

        return CreditCard\Validator::date($dateEx[0], $dateEx[1]);
    }

    /**
     * Trim data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function trim(string $data) : string
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
    public static function nc(string $data) : string
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
    public static function html(string $data) : string
    {
        return Security\Html::encode(Security\Html::decode($data));
    }

    /**
     * Encode cross site scripting.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function xss(string $data) : string
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
    public static function injection(string $data) : string
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
    public static function script(string $data) : string
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
    public static function php(string $data) : string
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
    public static function required(string $data) : bool
    {
        return $data !== '';
    }

    /**
     * Answer control
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function answer(string $data) : bool
    {
        if( empty($data) )
        {
            return false;
        }
        
        return $data == strtolower($_SESSION[md5('answerToQuestion')] ?? '');
    }

    /**
     * Question
     * 
     * @param array $question
     * 
     * @return string
     */
    public static function question(array $questions = [])
    {
        if( empty($questions) )
        {
            $question = rand(1, 9) . ' ' . ['*', '+'][rand(0, 1)] . ' ' . rand(1, 9);

            $_SESSION[md5('answerToQuestion')] = eval('return ' . $question . ';');

            return $question;
        }
        
        $index = rand(0, count($questions) - 1);

        $_SESSION[md5('answerToQuestion')] = strtolower(array_values($questions)[$index]);

        return array_keys($questions)[$index];
    }

    /**
     * Control captcha code.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function captcha(string $data) : bool
    {
        if( empty($data) )
        {
            return false;
        }
        
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
    public static function matchPassword(string $data, string $check) : bool
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
    public static function match(string $data, string $check) : bool
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
    public static function pattern(string $data, string $check) : bool
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
    public static function between(Float $value, Float $min, Float $max) : bool
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
    public static function betweenBoth(Float $value, Float $min, Float $max, $type = 'both') : bool
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
    public static function phone(string $data, string $pattern = NULL) : bool
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
    public static function numeric($data) : bool
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
    public static function alnum(string $data) : bool
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
    public static function alpha(string $data) : bool
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
    public static function identity($no) : bool
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
            return false; // @codeCoverageIgnore
        }
        elseif( $no[10] != $elewenth )
        {
            return false; // @codeCoverageIgnore
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
    public static function email(string $data) : bool
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
    public static function url(string $data) : bool
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
    public static function specialChar(string $data) : bool
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
    public static function maxchar(string $data, int $char) : bool
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
    public static function minchar(string $data, int $char) : bool
    {
        return ( strlen($data) >= $char );
    }
}
