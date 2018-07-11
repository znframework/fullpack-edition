<?php namespace ZN\Protection;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Ability\Container;

class Store
{
    use Container;

    /**
     * Magic Constructor
     * 
     * @param StoreInterface $store
     */
    public function __construct(StoreInterface $store = NULL)
    {
        self::$container = $store;
    }
}
