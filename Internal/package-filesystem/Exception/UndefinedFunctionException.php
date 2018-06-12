<?php namespace ZN\Filesystem\Exception;
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

class UndefinedFunctionException extends Exception
{
    const lang = 
    [
        'en' => 'Call to undefined function `%`!',
        'tr' => '%` fonksiyonu tanımlı değil!'
    ];
}
