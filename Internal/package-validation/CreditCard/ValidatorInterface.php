<?php namespace ZN\Validation\CreditCard;
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
     * Is valid card
     * 
     * @param string $data
     * @param string $type = NULL
     * 
     * @return bool
     */
    public static function card(String $data, String $type = NULL) : Bool;

    /**
     * Is valid cvc
     * 
     * @param int    $cvc
     * @param string $type
     */
    public static function cvc(Int $cvc, String $type = NULL) : Bool;

    /**
     * Is valid date
     * 
     * @param string $year
     * @param string $month
     * 
     * @return bool
     */
    public static function date(String $year, String $month) : Bool;
}
