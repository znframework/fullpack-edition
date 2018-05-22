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

use Html;
use Method;
use DB;
use DBForge;
use DBTool;
use Import;
use ZN\Base;
use ZN\Config;
use ZN\Model;
use ZN\DataTypes\Arrays\RemoveElement;

class Table extends Model
{
    public static function list()
    {
        return DBTool::listTables();
    }

    public static function create()
    {
        $table   = Method::post('table');
        $columns = RemoveElement::first(Method::post());
        $newData = [];
        $i       = 0;
        
        foreach( $columns as $key => $values )
        {
            foreach( $values as $value )
            {
                $newData[$i][$key] = $value;

                $i++;
            }

            $i = 0;
        }

        $newColumns = [];
        
        foreach( $newData as $data )
        {
            $maxLength = $data['maxLength'];
            $type      = $data['type'];

            if( in_array($type, ['DATE', 'DATETIME', 'TIME', 'TIMESTAMP']) )
            {
                $maxLength = 0;
            }

            $newColumns[$data['columnName']] =
            [
                ( $type ?: '' ).( ! empty($maxLength ) ? '('.$maxLength .')' : '' ),
                ! empty($data['primaryKey'])      ? DB::primaryKey()    : NULL,
                ! empty($data['autoIncrement'])   ? DB::autoIncrement() : NULL,
                $data['isNull'] === DB::notNull() ? DB::notNull()       : '',
                ! empty($default)                 ? 'DEFAULT '.$default : ''
            ];
        }

        $driver = Config::database('database')['driver'];

        if( $driver === 'postgres' || $driver === 'sqlite' )
        {
            $encoding = NULL;
        }
        else
        {
            $encoding = DB::encoding();
        }

        $status = DBForge::createTable($table, $newColumns, $encoding);
        $result = Import::view('datatables-tables.wizard', ['tables' => DBTool::listTables()], true);

        echo json_encode
        ([
            'status' => $status,
            'result' => $result,
            'error'  => DBForge::error()
        ]);
    }

    public static function alter()
    {
        $content = Html::decode(Method::post('content'));
        $type    = Method::post('type');

        if( $type === 'orm' )
        {
            $status  = eval('?><?php ' . Base::suffix($content, ';'));
        }
        else
        {
            $status = DB::query($content);
        }

        $result = Import::usable()->view('datatables-tables.wizard', ['tables' => DBTool::listTables()]);

        echo json_encode
        ([
            'status' => $status,
            'result' => $result,
            'error'  => DBForge::error() . DB::error() . DBTool::error()
        ]);
    }

    public static function drop()
    {
        $table   = Method::post('table');
        $status  = DBForge::dropTable($table);
        $result  = Import::usable()->view('datatables-tables.wizard', ['tables' => DBTool::listTables()]);

        echo json_encode
        ([
            'status' => $status,
            'result' => $result,
            'error'  => DBForge::error()
        ]);
    }
}