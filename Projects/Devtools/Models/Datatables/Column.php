<?php namespace Datatables;
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
use DBForge;
use DB;
use Session;
use Import;
use ZN\Model;

class Column extends Model
{
    public static function modify()
    {
        $table      = Method::post('table');
        $column     = Method::post('column');
        $columnName = Method::post('columnName');
        $type       = trim(Method::post('type'));
        $maxLength  = Method::post('maxLength');
        $isNull     = Method::post('isNull');
        $default    = Method::post('defaul');

        if( in_array($type, ['DATE', 'DATETIME', 'TIME', 'TIMESTAMP']) )
        {
            $maxLength = 0;
        }

        $columns =
        [
            $type.( ! empty($maxLength) ? '('.$maxLength.')' : '' ),
            $isNull === DB::notNull() ? DB::notNull()  : '',
            ! empty($default) ? 'DEFAULT '.$default    : ''
        ];

        if( $column !== 'add-column')
        {
            if( $column !== $columnName )
            {
                DBForge::renameColumn($table, [$column . ' ' . $columnName => $columns]);
            }
            else
            {
                DBForge::modifyColumn($table, [$column => $columns]);
            }
        }
        else
        {

            DBForge::addColumn($table, [$columnName => $columns]);
        }

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')]);
    }

    public static function drop()
    {
        $table  = Method::post('table');
        $column = Method::post('column');

        DBForge::dropColumn($table, $column);

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')]);
    }
}