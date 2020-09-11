<?php namespace ZN\Payment\Exception;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Exception;

class InvalidCardTypeException extends Exception
{
    const lang = 
    [
        'tr' => '[%] bilgisi geçerli bir kart türü değildir! Kullanılabilir Seçenekler:[visa, mastercard]', 
        'en' => '[%] information is not a valid card type! Available Options[visa, mastercard]'
    ];
}
