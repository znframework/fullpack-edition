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
        $data = Method::post();

        extract($data);
        
        $type = trim($type);

        if( in_array($type, ['DATE', 'DATETIME', 'TIME', 'TIMESTAMP']) )
        {
            $maxLength = 0;
        }

        $columns =
        [
            Table::columnType($type, $maxLength),
            Table::columnIsNull($isNull),
            Table::columnDefault($default)
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