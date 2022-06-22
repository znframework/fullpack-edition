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
    public static function check(string $number = NULL)
    {
        $table = 
        [
			[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
			[0, 2, 4, 6, 8, 1, 3, 5, 7, 9]
		];

		$total = 0; $transform = 0;

		for( $i = strlen($number ?? '') - 1; $i >= 0; $i-- )
		{
			$total += $table[$transform++ & 0x1][$number[$i]];
		}

		return $total % 10 === 0;
    }
}
