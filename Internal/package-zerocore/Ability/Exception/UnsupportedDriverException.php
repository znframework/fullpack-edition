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

class UnsupportedDriverException extends Exception
{
    /**
     * Exception language settings
     * 
     * @param string en
     * @param string tr
     */
    const lang = 
    [
        'en' => '[%] driver is not a valid driver for class [#]!',
        'tr' => '[%] sürücüsü [#] sınıfı için geçerli bir sürücü değil!'
    ];
}
