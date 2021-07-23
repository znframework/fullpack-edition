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

class InvalidSendTypeException extends Exception
{
    const lang = 
    [
        'tr' => '[%] bilgisi geçerli bir tür değildir!', 
        'en' => '[%] information is not a valid type!'
    ];
}
