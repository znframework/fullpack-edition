<?php namespace ZN\Routing;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Request;

class UsableFilter
{
    public function __construct($filters, $get, $config, $class)
    {
        if( strpos(strtolower(Request::getActiveURI()), Base::removeSuffix(CURRENT_CFURI, '/main')) === 0 && $get === false )
        {
            $class->redirectRequest();
        }
    }
}
