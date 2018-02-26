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

class Total extends CartExtends
{
    //--------------------------------------------------------------------------------------------------------
    // Total Items
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function items() : Int
    {
        $totalItems = 0;

        if( ! empty(Properties::$items) ) foreach( Properties::$items as $item )
        {
            $totalItems += $item['quantity'];
        }

        return $totalItems;
    }


    //--------------------------------------------------------------------------------------------------------
    // Total Prices
    //--------------------------------------------------------------------------------------------------------
    //
    // @param mixed $code
    //
    //--------------------------------------------------------------------------------------------------------
    public function prices() : Int
    {
        $total = 0;

        if( ! empty(Properties::$items) ) foreach( Properties::$items as $values )
        {
            $quantity = (int)   ($values['quantity'] ?? 1);
            $price    = (float) ($values['price']    ?? 0);

            $total += $price * $quantity;
        }

        return $total;
    }
}
