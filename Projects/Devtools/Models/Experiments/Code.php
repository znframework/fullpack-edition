<?php namespace Experiments;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use Post;
use DB;
use Import;
use ZN\Model;

class Code extends Model
{
    public static function run()
    {
        $content = Post::content();
        $type    = Post::type();

        if( $type === 'php' )
        {
            eval('?>' . html_entity_decode($content, ENT_QUOTES)); exit;
        }
        else
        {
            $query = DB::query($content);

            $result = Import::view('experiments-table', ['columns' => $query->columns(), 'result' => $query->resultArray()], true);
        }

        echo $result ?: LANG['noOutput'];
    }
}