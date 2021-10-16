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
    public static function card(string $data, string $type = NULL) : bool;

    /**
     * Is valid cvc
     * 
     * @param string $cvc
     * @param string $type
     */
    public static function cvc(string $cvc, string $type = NULL) : bool;

    /**
     * Is valid date
     * 
     * @param string $year
     * @param string $month
     * 
     * @return bool
     */
    public static function date(string $year, string $month) : bool;
}
