<?php namespace ZN\Ability\Exception;
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

class InvalidFactoryMethod extends Exception
{
    const lang = 
    [
        'tr' => '[%] parametre geçersiz fabrika yöntemi içeriyor!', 
        'en' => '[%] parameter contains invalid factory method!'
    ];
}
