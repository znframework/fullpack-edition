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

interface ValidatorInterface
{
    /**
     * Trim data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function trim(String $data) : String;

    /**
     * Nasty code clean.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function nc(String $data) : String;

    /**
     * Encode html tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function html(String $data) : String;

    /**
     * Encode cross site scripting.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function xss(String $data) : String;

    /**
     * Encode injection data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function injection(String $data) : String;

    /**
     * Encode script tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function script(String $data) : String;

    /**
     * Encode PHP tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function php(String $data) : String;

    /**
     * Empty control.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function required(String $data) : Bool;

    /**
     * Control captcha code.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function captcha(String $data) : Bool;

    /**
     * Match password.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function matchPassword(String $data, String $check) : Bool;

    /**
     * Match.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function match(String $data, String $check) : Bool;

    /**
     * Pattern.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function pattern(String $data, String $check) : Bool;

    /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function between(Float $value, Float $min, Float $max) : Bool;

   /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function betweenBoth(Float $value, Float $min, Float $max) : Bool;

    /**
     * Checks whether the donation has phone information.
     * 
     * @param string $data
     * @param string $desing = NULL
     * 
     * @return bool
     */
    public static function phone(String $data, String $pattern = NULL) : Bool;

    /**
     * The data should be numeric.
     * 
     * @param mixed $data
     * 
     * @return bool
     */
    public static function numeric($data) : Bool;

    /**
     * Checks whether the verb is alphabetic.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alnum(String $data) : Bool;

    /**
     * Controls whether the verb is alphanumeric.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alpha(String $data) : Bool;

    /**
     * The citizenship identification number checks.
     * 
     * @param string $no 
     * 
     * @return bool
     */
    public static function identity($no) : Bool;

    /**
     * Checks whether the email is an e-mail.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function email(String $data) : Bool;

    /**
     * Checks whether the data is url.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function url(String $data) : Bool;

    /**
     * Checks whether the data is special char.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function specialChar(String $data) : Bool;

    /**
     * Makes the maximum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function maxchar(String $data, Int $char) : Bool;

    /**
     * Makes the minimum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function minchar(String $data, Int $char) : Bool;
}
