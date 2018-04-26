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

class LuhnAlgorithm implements LuhnAlgorithmInterface
{   
    /**
     * Protected luhn algorithm check
     * 
     * @param string $number
     * 
     * @return string
     */
    public static function check(String $number = NULL)
    {
        $checksum = 0;

        for( $i = (2 - (strlen($number) % 2)); $i <= strlen($number); $i += 2 ) 
        {
            $checksum += (int) ($number{$i-1});
        }

        for( $i = (strlen($number) % 2) + 1; $i < strlen($number); $i += 2 ) 
        {
            $digit = (int) ($number{$i-1}) * 2;

            if( $digit < 10 ) 
            {
                $checksum += $digit;
            } 
            else 
            {
                $checksum += ($digit-9);
            }
        }

        return ($checksum % 10) == 0;
    }
}
