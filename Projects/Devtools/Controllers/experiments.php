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

use Http;
use Experiments as ExperimentsModel;

class Experiments extends Controller
{
    /**
     * Main
     */
    public function main(String $params = NULL)
    {
        Masterpage::page('experiment');
    }

    /**
     * Ajax Run Code
     */
    public function runCode()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        # Run experiments code - options[php|sql]
        ExperimentsModel\Code::run();
    }
}
