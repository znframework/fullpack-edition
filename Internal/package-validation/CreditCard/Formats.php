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

class Formats implements FormatsInterface
{
    /**
     * Visa Electron Format
     * 
     * @var array
     */
    public static $visaelectron = 
    [
        'type'      => 'visaelectron',
        'pattern'   => '/^4(026|17500|405|508|844|91[37])/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Maestro Format
     * 
     * @var array
     */
    public static $maestro =
    [
        'type'      => 'maestro',
        'pattern'   => '/^(5(018|0[23]|[68])|6(39|7))/',
        'length'    => [12, 13, 14, 15, 16, 17, 18, 19],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Forbrugs Foreningen Format
     * 
     * @var array
     */
    public static $forbrugsforeningen = 
    [
        'type'      => 'forbrugsforeningen',
        'pattern'   => '/^600/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Dankort
     * 
     * @var array
     */
    public static $dankort = 
    [
        'type'      => 'dankort',
        'pattern'   => '/^5019/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Visa Format
     * 
     * @var array
     */
    public static $visa = 
    [
        'type'      => 'visa',
        'pattern'   => '/^4/',
        'length'    => [13, 16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Mastercard Format
     * 
     * @var array
     */
    public static $mastercard = 
    [
        'type'      => 'mastercard',
        'pattern'   => '/^(5[0-5]|2[2-7])/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Amex Format
     * 
     * @var array
     */
    public static $amex = 
    [
        'type'      => 'amex',
        'pattern'   => '/^3[47]/',
        'format'    => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
        'length'    => [15],
        'cvcLength' => [3, 4],
        'luhn'      => true
    ];

    /**
     * Diners Club Format
     * 
     * @var array
     */
    public static $dinersclub = 
    [
        'type'      => 'dinersClub',
        'pattern'   => '/^3[47]/',
        'format'    => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
        'length'    => [15],
        'cvcLength' => [3, 4],
        'luhn'      => true
    ];

    /**
     * Discover Format
     * 
     * @var array
     */
    public static $discover = 
    [
        'type'      => 'discover',
        'pattern'   => '/^6([045]|22)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Unionpay Format
     * 
     * @var array
     */
    public static $unionpay = 
    [
        'type'      => 'unionpay',
        'pattern'   => '/^(62|88)/',
        'length'    => [16, 17, 18, 19],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * JCB Format
     * 
     * @var array
     */
    public static $jcb = 
    [
        'type'      => 'jcb',
        'pattern'   => '/^35/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Return all card formats
     * 
     * @return array
     */
    public static function getList() : Array
    {
        return get_class_vars(self::class);
    }
}
