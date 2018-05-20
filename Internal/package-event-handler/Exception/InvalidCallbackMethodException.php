<?php namespace ZN\EventHandler\Exception;
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

class InvalidCallbackMethodException extends Exception
{
    const lang = 
    [
        'en' => 'A callable callback must be specified!',
        'tr' => 'Bir geri dönüş çağrısı belirtilmelidir!'
    ];
}
