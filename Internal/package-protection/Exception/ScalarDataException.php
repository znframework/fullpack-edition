<?php namespace ZN\Protection\Exception;
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

class ScalarDataException extends Exception
{
    const lang = 
    [
        'tr' => '[%] bilgisi array veya object türü olmalıdır!', 
        'en' => '[%] information must be array or object type!'
    ];
}
