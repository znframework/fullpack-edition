<?php namespace Home;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Session;
use Restful;
use ZN\Model;

class Statistics extends Model
{
    public static function get()
    {
        if( ! $return = Session::select('return') )
        {
            $return = Restful::get('https://api.znframework.com/statistics');

            Session::insert('return', $return);
        }
    }
}