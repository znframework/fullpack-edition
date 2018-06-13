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

class FileNotFoundException extends Exception
{
    const lang = 
    [
        'en' => '`%` file was not found!',
        'tr' => '`%` dosyasi bulunamadi!'
    ];
}
