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
use DB;
use Session;
use Import;
use ZN\Model;

class Row extends Model
{
    public static function add()
    {
        $post = Method::post();

        $columns = $post['addColumns'];
        $table   = $post['table'];

        DB::insert('ignore:' . $table, $columns);

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')]);
    }

    public static function update()
    {
        $table   = Method::post('table');
        $column  = Method::post('uniqueKey');
        $ids     = Method::post('ids');
        $columns = $_POST['updateColumns'][$ids]; # Origin Data

        DB::where($column, $ids)->update('ignore:' . $table, $columns);

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')]);
    }

    public static function updateAll()
    {
        $post = Method::post();

        $columns   = $post['columns'];
        $table     = $post['table'];
        $uniqueKey = $post['uniqueKey'];
        $newData   = [];

        $i = 0;

        foreach( $columns as $key => $values )
        {
            foreach( $values as $value )
            {
                $newData[$i][$key] = $value;

                DB::where($uniqueKey, $newData[$i][$uniqueKey])->update($table, $newData[$i]);

                $i++;
            }

            $i = 0;
        }

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')], true);
    }

    public static function delete()
    {
        $table  = Method::post('table');
        $column = Method::post('column');
        $value  = Method::post('value');

        DB::where($column, $value)->delete($table);

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => (int) Session::select($table . 'paginationStart')]);
    }

    public static function pagination()
    {
        $table = Method::post('table');
        $start = Method::post('start');

        Session::insert($table . 'paginationStart', $start);

        Import::view('datatables-rows.wizard', ['table' => $table, 'start' => $start]);
    }
}