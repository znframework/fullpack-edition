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

class Validator implements ValidatorInterface
{   
    /**
     * Is valid card
     * 
     * @param string $number
     * @param string $type = NULL
     * 
     * @return bool
     */
    public static function card(String $number, String $type = NULL) : Bool
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if( empty($type) ) 
        {
            $type = self::creditCardType($number);
        }

        if( array_key_exists($type, self::getCardFormats()) && self::validCard($number, $type) ) 
        {
            return true;
        }

        return false;
    }

    /**
     * Is valid cvc
     * 
     * @param int    $cvc
     * @param string $type
     */
    public static function cvc(Int $cvc, String $type = NULL) : Bool
    {
        return ctype_digit($cvc) && array_key_exists($type, self::getCardFormats()) && self::validCvcLength($cvc, $type);
    }

    /**
     * Is valid date
     * 
     * @param string $year
     * @param string $month
     * 
     * @return bool
     */
    public static function date(String $year, String $month) : Bool
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        if
        ( 
            ! preg_match('/^20\d\d$/', $year)           || 
            ! preg_match('/^(0[1-9]|1[0-2])$/', $month) ||
            ( $year < date('Y') || ($year <= date('Y') && $month < date('m')) ) 
        ) 
        {
            return false;
        }

        return true;
    }

    /**
     * Protected credit card type
     * 
     * @param string $number
     * 
     * @return string
     */
    protected static function creditCardType(String $number) : String
    {
        foreach( self::getCardFormats() as $type => $card ) 
        {
            if( preg_match($card['pattern'], $number) ) 
            {
                return $type;
            }
        }

        return '';
    }

    /**
     * Protected valid card
     * 
     * @param string $number
     * @param string $type
     * 
     * @return string
     */
    protected static function validCard(String $number, String $type = NULL) : Bool
    {
        return self::validCardFormat($number, $type) && self::validCardNumberLength($number, $type) && self::validLuhnAlgorithm($number, $type);
    }
    
    /**
     * Protected valid card pattern
     * 
     * @param string $number
     * @param string $type
     * 
     * @return bool
     */
    protected static function validCardFormat($number, $type) : Bool
    {
        return preg_match(self::getCardFormats()[$type]['pattern'], $number);
    }

    /**
     * Protected valid card number length
     * 
     * @param string $number
     * @param string $type
     * 
     * @return bool
     */
    protected static function validCardNumberLength($number, String $type = NULL, $function = 'length') : Bool
    {
        return in_array(strlen($number), self::getCardFormats()[$type][$function]);
    }

    /**
     * Protected valid cvc length
     * 
     * @param int    $cvc
     * @param string $type
     * 
     * @return bool
     */
    protected static function validCvcLength($cvc, String $type = NULL) : Bool
    {
        return self::validCardNumberLength($cvc, $type, 'cvcLength');
    }

    /**
     * Protected valid luhn algorithm
     * 
     * @param string $number
     * @param string $type
     * 
     * @return bool
     */
    protected static function validLuhnAlgorithm(String $number, String $type = NULL) : Bool
    {
        return self::getCardFormats()[$type]['luhn'] ? LuhnAlgorithm::check($number) : true;
    }

    /**
     * Protected get card formats
     * 
     * @return array
     */
    protected static function getCardFormats() : Array
    {
        return Formats::getList();
    }
}
