<?php namespace ZN\Cache\Exception;
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

class InvalidTimeException extends Exception
{
    const lang = 
    [
        'en' => '[%] information is invalid time format!',
        'tr' => '[%] bilgisi geçersiz zaman formatıdır!'
    ];
}
