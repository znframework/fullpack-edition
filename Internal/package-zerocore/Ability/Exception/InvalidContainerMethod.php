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

class InvalidContainerMethod extends Exception
{
    const lang = 
    [
        'tr' => '[#::%()] yöntemi tanımlı değil!', 
        'en' => 'The [#::%()] method is undefined!'
    ];
}
