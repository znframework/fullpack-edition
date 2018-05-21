<?php namespace Project\Controllers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Method;
use Restful;
use Api as ApiModel;

class Api extends Controller
{
    /**
     * Main
     */
    public function main()
    {
        # Depending on the request, the data is being sent.
        if( Method::post('request') )
        {
            ApiModel\Request::send();
        }

        # The corresponding view is being loaded.
        Masterpage::page('rest-api');
    }
}
