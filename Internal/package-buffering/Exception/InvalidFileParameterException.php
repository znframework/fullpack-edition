<?php namespace ZN\Buffering\Exception;
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

class InvalidFileParameterException extends Exception
{
    const lang = 
    [
        'en' => '`%` parameter should contain the file data type!',
        'tr' => '`%` parametresi dosya bilgisi içermelidir!'
    ];
}
