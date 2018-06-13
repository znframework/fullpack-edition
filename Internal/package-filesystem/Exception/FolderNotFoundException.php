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

class FolderNotFoundException extends Exception
{
    const lang = 
    [
        'en' => '`%` directory was not found!',
        'tr' => '`%` dizini bulunamadi!'
    ];
}
