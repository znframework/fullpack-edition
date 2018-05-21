<?php namespace Initialize;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Base;
use ZN\Model;

class Database extends Model
{
    public static function config()
    {
        $databaseConfigPath = SELECT_PROJECT_DIR . 'Config' . DS . 'Database.php';

        if( IS_CONTAINER )
        {
            $databaseConfigPath = str_replace(SELECT_PROJECT, IS_CONTAINER, $databaseConfigPath);
        }

        if( SELECT_PROJECT !== 'External' )
        {
            Config::set('Database', Base::import($databaseConfigPath));
        }

        define('CURRENT_DATABASE', Config::get('Database', 'database')['database']);
    }
}