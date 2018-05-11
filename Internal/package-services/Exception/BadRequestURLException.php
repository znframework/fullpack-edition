<?php namespace ZN\Services\Exception;
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

class BadRequestURLException extends Exception
{
    const lang = 
    [
        'en' => 'The [%] address contains invalid URL information!',
        'tr' => '[%] adresi geçersiz bir URL bilgisi içermektedir!'
    ];
}
