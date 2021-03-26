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
    public static function trim(string $data) : string;

    /**
     * Nasty code clean.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function nc(string $data) : string;

    /**
     * Encode html tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function html(string $data) : string;

    /**
     * Encode cross site scripting.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function xss(string $data) : string;

    /**
     * Encode injection data.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function injection(string $data) : string;

    /**
     * Encode script tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function script(string $data) : string;

    /**
     * Encode PHP tags.
     * 
     * @param string $data
     * 
     * @return string
     */
    public static function php(string $data) : string;

    /**
     * Empty control.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function required(string $data) : bool;

    /**
     * Control captcha code.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function captcha(string $data) : bool;

    /**
     * Question
     * 
     * @array $question
     * 
     * @return string
     */
    public static function question(array $questions = []);

    /**
     * Answer control
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function answer(string $data) : bool;

    /**
     * Match password.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function matchPassword(string $data, string $check) : bool;

    /**
     * Match.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function match(string $data, string $check) : bool;

    /**
     * Pattern.
     * 
     * @param string $data
     * @param string $check
     * 
     * @return bool
     */
    public static function pattern(string $data, string $check) : bool;

    /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function between(Float $value, Float $min, Float $max) : bool;

   /**
     * Checks whether the grant is between the specified values.
     * 
     * @param float $value
     * @param float $min 
     * @param float $max
     * 
     * @return bool
     */
    public static function betweenBoth(Float $value, Float $min, Float $max) : bool;

    /**
     * Checks whether the donation has phone information.
     * 
     * @param string $data
     * @param string $desing = NULL
     * 
     * @return bool
     */
    public static function phone(string $data, string $pattern = NULL) : bool;

    /**
     * The data should be numeric.
     * 
     * @param mixed $data
     * 
     * @return bool
     */
    public static function numeric($data) : bool;

    /**
     * Checks whether the verb is alphabetic.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alnum(string $data) : bool;

    /**
     * Controls whether the verb is alphanumeric.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function alpha(string $data) : bool;

    /**
     * The citizenship identification number checks.
     * 
     * @param string $no 
     * 
     * @return bool
     */
    public static function identity($no) : bool;

    /**
     * Checks whether the email is an e-mail.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function email(string $data) : bool;

    /**
     * Checks whether the data is url.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function url(string $data) : bool;

    /**
     * Checks whether the data is special char.
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function specialChar(string $data) : bool;

    /**
     * Makes the maximum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function maxchar(string $data, int $char) : bool;

    /**
     * Makes the minimum character limit.
     * 
     * @param string $data
     * @param int    $char
     * 
     * @return bool
     */
    public static function minchar(string $data, int $char) : bool;
}
