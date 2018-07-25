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

class ActivationReturnLinkNotFoundException extends Exception
{
    const lang = 
    [
        'tr' => 'Aktivasyon işlemi için dönüş linki belirtilmelidir!', 
        'en' => 'The return link must be specified for the activation process!'
    ];
}
