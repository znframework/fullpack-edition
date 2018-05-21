<?php namespace Home;

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