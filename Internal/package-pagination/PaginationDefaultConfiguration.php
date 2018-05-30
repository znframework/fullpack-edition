<?php namespace ZN\Pagination;
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
 * Enabled when the configuration file can not be accessed.
 */
class PaginationDefaultConfiguration
{
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Includes default settings for the paging view.
    |
    */

    public $prevName      = '&laquo;';
    public $nextName      = '&raquo;';
    public $firstName     = '&laquo;&laquo;';
    public $lastName      = '&raquo;&raquo;';
    public $totalRows     = 50;
    public $start         = NULL;
    public $limit         = 10;
    public $countLinks    = 10;
    public $type          = 'classic';   # classic, ajax
    public $output        = 'bootstrap'; # classic, bootstrap
    public $class         =
    [
        'current'   => 'active',
        'links'     => '',
        'prev'      => '',
        'next'      => '',
        'last'      => '',
        'first'     => ''
    ];
    public $style         =
    [
        'current'   => '',
        'links'     => '',
        'prev'      => '',
        'next'      => '',
        'last'      => '',
        'first'     => ''
    ];
}
