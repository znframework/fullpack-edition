<?php namespace Project\Commands;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use GeneralException;

class Command extends \BaseController
{
    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        if( server('documentRoot') )
        {
            throw new GeneralException
            (
                'Commands',
                'canNotCommandClass'
            );
        }
    }
}
