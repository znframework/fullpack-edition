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
     * Maestro Format
     * 
     * @var array
     */
    public static $maestro =
    [
        'type'      => 'maestro',
        'pattern'   => '/^(51|52|53|54|55|22|23|24|25|26|27)/',
        'length'    => [12, 13, 14, 15, 16, 18, 19],
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
        'pattern'   => '/^(5019|4175|4571|4)/',
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
        'length'    => [13, 16, 19],
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
        'pattern'   => '/^(51|52|53|54|55|22|23|24|25|26|27)/',
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
        'pattern'   => '/^(34|37)/',
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
        'pattern'   => '/^(300|301|302|303|304|305|309|36|38|39|54|55)/',
        'length'    => [14, 16],
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
        'pattern'   => '/^(6011|622|644|645|656|647|648|649|65)/',
        'length'    => [16, 19],
        'cvcLength' => [3, 4],
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
        'pattern'   => '/^(62)/',
        'length'    => [16, 17, 18, 19],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * JCB Format
     * 
     * @var array
     */
    public static $jcb = 
    [
        'type'      => 'jcb',
        'pattern'   => '/^(352|353|354|355|356|357|358)/',
        'length'    => [16,17,18,19],
        'cvcLength' => [3, 4],
        'luhn'      => true
    ];

    /**
     * DinersClub CarteBlanche
     * 
     * @var array
     */
    public static $carteblanche = 
    [
        'type'      => 'carteblanche',
        'pattern'   => '/^(300|301|302|303|304|305)/',
        'length'    => [14],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * InterPayment
     * 
     * @var array
     */
    public static $interpayment = 
    [
        'type'      => 'interpayment',
        'pattern'   => '/^4/',
        'length'    => [16,17,18,19],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * NSPK MIR
     * 
     * @var array
     */
    public static $mir = 
    [
        'type'      => 'mir',
        'pattern'   => '/^(2200|2201|2202|2203|2204)/',
        'length'    => [16],
        'cvcLength' => [3, 4],
        'luhn'      => true
    ];

    /**
     * Troy
     * 
     * @var array
     */
    public static $troy = 
    [
        'type'      => 'troy',
        'pattern'   => '/^(979200|979289)/',
        'length'    => [16],
        'cvcLength' => [3, 4],
        'luhn'      => true
    ];

    /**
     * UATP
     * 
     * @var array
     */
    public static $uatp = 
    [
        'type'      => 'uatp',
        'pattern'   => '/^1/',
        'length'    => [15],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * Verve
     * 
     * @var array
     */
    public static $verve = 
    [
        'type'      => 'verve',
        'pattern'   => '/^(506|650)/',
        'length'    => [16, 19],
        'cvcLength' => [3],
        'luhn'      => true
    ];

    /**
     * BMO ABM Card
     * 
     * @var array
     */
    public static $bmoabm = 
    [
        'type'      => 'bmoabm',
        'pattern'   => '/^(500)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * CIBC Convenience Card
     * 
     * @var array
     */
    public static $cibc = 
    [
        'type'      => 'cibc',
        'pattern'   => '/^(4506)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * HSBC Canada Card
     * 
     * @var array
     */
    public static $hsbc = 
    [
        'type'      => 'hsbc',
        'pattern'   => '/^(56)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * Royal Bank of Canada Client Card
     * 
     * @var array
     */
    public static $rbc = 
    [
        'type'      => 'rbc',
        'pattern'   => '/^(45)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * Scotiabank Scotia Card
     * 
     * @var array
     */
    public static $scotia = 
    [
        'type'      => 'scotia',
        'pattern'   => '/^(4536)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * TD Canada Trust Access Card
     * 
     * @var array
     */
    public static $tdtrust = 
    [
        'type'      => 'tdtrust',
        'pattern'   => '/^(589297)/',
        'length'    => [16],
        'cvcLength' => [3],
        'luhn'      => false
    ];

    /**
     * Return all card formats
     * 
     * @return array
     */
    public static function getList() : array
    {
        return get_class_vars(self::class);
    }
}
