<?php namespace ZN\DateTime\Exception;
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

class InvalidDateException extends Exception
{
    const lang = 
    [
        'tr' => '% bilgisi geçerli bir [tarih/zaman] değildir!', 
        'en' => '% information is not a valid [date/time]!'
    ];
}
