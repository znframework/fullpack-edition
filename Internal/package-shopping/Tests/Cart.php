<?php namespace ZN\Shopping\Tests;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Controller\UnitTest;

class Cart extends UnitTest
{
    const unit =
    [
        'class'   => 'Cart',
        'methods' => 
        [
            'insertItem'  => [['name' => 'orange', 'quantity' => 2, 'price' => 100]],
            'selectItem'  => ['orange'],
            'selectItems' => [],
            'deleteItem'  => ['orange'],
            'deleteItems' => []
        ]
    ];
}
