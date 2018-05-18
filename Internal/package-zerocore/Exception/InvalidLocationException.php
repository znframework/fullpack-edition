<?php namespace ZN\Exception;
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

class InvalidLocationException extends Exception
{
    const lang = 
    [
        'tr' => 'The location can be one of [project] or [external]!', 
        'en' => 'Konum [project] veya [external] değerlerinden biri olabilir!'
    ];
}
