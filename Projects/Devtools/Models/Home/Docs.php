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

use Method;
use Restful;
use File;
use Json;
use Masterpage;
use ZN\Model;

class Docs extends Model
{
    public static function get()
    {
        $docs = FILES_DIR . 'docs.json';

        $return = [];

        if( Method::post('refresh') || ! file_exists($docs) )
        {
            if( $return = Restful::get('https://api.znframework.com/docs') )
            {
                File::write($docs, Json::encode($return));
            }
            else
            {
                Masterpage::error(LANG['docsRetrievalFailed']);
            }
        }
        else
        {
            $return = Json::decode(File::read($docs));
        }

        return $return;
    }
}