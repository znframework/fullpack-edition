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

use Restful;
use Import;
use Redirect;

class Version extends Controller
{
    /**
     * Notes Page
     */
    public function notes(String $params = NULL)
    {
        Import::handload('Functions');
        
        Masterpage::page('versions-notes');
    }
}
