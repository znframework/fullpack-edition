<?php namespace ZN\Cryptography\Exception;
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

class InvalidCipherMethodException extends Exception
{
    const lang =
    [
        'en' => '[%] Invalid is cipher method!',
        'tr' => '[%] Geçersiz şifreleme yöntemi!'
    ];
}
