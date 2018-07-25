<?php namespace ZN\Authentication\Exception;
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

class InvalidEmailException extends Exception
{
    const lang = 
    [
        'tr' => '[%] parametresi geçersiz bir e-posta adresidir!', 
        'en' => '[%] parameter is an invalid email address!'
    ];
}
