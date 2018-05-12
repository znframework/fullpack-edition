<?php namespace ZN\Shopping;
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
class ShoppingDefaultConfiguration
{
    /*
    |--------------------------------------------------------------------------
    | Shopping
    |--------------------------------------------------------------------------
    |
    | It is specified in which structure the cart data information will be stored.
    |
    */

    public $driver = 'session';
}
