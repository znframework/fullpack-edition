<?php namespace Project\Controllers;

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
