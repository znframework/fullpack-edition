<?php namespace ZN\ErrorHandling;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

/**
 * Default Configuration
 * 
 * Provides predefined language content for core classes.
 */
class ErrorHandlingDefaultLanguage
{
    /*
    |--------------------------------------------------------------------------
    | Butcher
    |--------------------------------------------------------------------------
    |
    | The language of the Core structures.
    |
    */

    public $en = 
    [
        'type'    => 'Type',
        'line'    => 'Line',
        'message' => 'Error',
        'file'    => 'File',
        'trace'   => 'Trace'
    ];

    public $tr = 
    [
        'type'    => 'Tür',
        'line'    => 'Satır',
        'message' => 'Hata',
        'file'    => 'Dosya',
        'trace'   => 'İz'
    ];
}
