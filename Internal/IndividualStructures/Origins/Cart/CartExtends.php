<?php namespace ZN\IndividualStructures\Cart;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class CartExtends
{
    use \DriverAbility;

    /**
     * Driver
     * 
     * @param array  options
     * @param string construct
     */
    const driver =
    [
        'options'   => ['session', 'cookie'],
        'construct' => 'constructor',
        'config'    => 'IndividualStructures:cart'
    ];

    /**
     *  Constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function constructor()
    {
        $this->key = md5('SystemCartData');

        if( $sessionCart = $this->driver->select($this->key) )
        {
            Properties::$items = $sessionCart;
        }
    }
}
