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

interface LuhnAlgorithmInterface
{   
    /**
     * Protected luhn algorithm check
     * 
     * @param string $number
     * 
     * @return string
     */
    public static function check(String $number = NULL);
}
