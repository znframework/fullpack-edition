<?php namespace Project\Controllers;
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
use Folder;
use File;
use Html;
use Arrays;
use Restful;
use Separator;
use Redirect;
use URI;
use Http;
use Session;
use DBTool;
use DB;
use URL;
use Form;
use DBGrid;
use Security;
use Config;
use Json;
use Lang;
use ZN\Base;
use ZN\ZN;

class System extends Controller
{
    /**
     * Converter Page
     */
    public function converter(String $params = NULL)
    {
        if( Method::post('convert') )
        {
            $orm = trim(Method::post('sql'));

            $this->_cselect($orm);
            $this->_cdelete($orm);
            $this->_cinsert($orm);
            $this->_cupdate($orm);
            $this->_ccreateTable($orm);
            $this->_cdropTable($orm);
            $this->_ccreateDatabase($orm);
            $this->_cdropDatabase($orm);

            $orm = Base::suffix(str_replace('->', '<br>&nbsp;&nbsp;->', $orm), ';');

            $pdata['orm'] = $orm;
        }

        $pdata['supportQueries'] =
        [
            '<b>select</b> columns <b>from</b> table_name [<b>where</b> column cond value] [<b>limit</b> start, limit] [<b>group by</b> column] [<b>order by</b> column asc|desc]',
            '<b>insert into</b> table_name (col1, col2, ...) <b>values</b>(val1, val2, ...)',
            '<b>update</b> table_name <b>set</b> column1 = value1 ... [<b>where</b> column cond value]',
            '<b>delete</b> <b>from</b> table_name <b>where</b> column cond value',
            '<b>create</b> <b>table</b> table_name (columns ... values)',
            '<b>drop</b> <b>table</b> table_name',
            '<b>create</b> <b>database</b> database_name',
            '<b>drop</b> <b>database</b> database_name'
        ];

        Masterpage::page('converter');

        Masterpage::pdata($pdata);
    }

    /**
     * Language Page
     */
    public function language(String $params = NULL)
    {
        $pdata['table']  = \MLS::limit(DASHBOARD_CONFIG['limits']['language'])->create();

        Masterpage::page('language');

        Masterpage::pdata($pdata);
    }

    /**
     * Grid Page
     */
    public function grid(String $params = NULL)
    {
        $tables             = DBTool::listTables();
        $sessionSelectTable = Session::select('gridSelectTable');
        $joinings           = Session::select('joinings');
        $searching          = Session::select('searching');
        $viewColumns        = Session::select('viewColumns');
        $selectTable        = ! empty($sessionSelectTable ) ? $sessionSelectTable : ($tables[0] ?? NULL);

        if( empty($selectTable) )
        {
            return Masterpage::error(Lang::select('DevtoolsErrors', 'gridError'));
        }

        $joinCollapse       = Session::select('joinCollapse');

        $pdata['tables'] = Arrays::combine($tables, $tables);

        if( Method::post('show') )
        {
            Session::delete('joinings');
            Session::delete('searching');
            Session::delete('viewColumns');
            Session::delete('joinCollapse');

            $joinCollapse = Security::htmlDecode(Method::post('joinsCollapse'));
            Session::insert('joinCollapse', $joinCollapse);

            $joinings        = [];
            $columns         = [];
            $searching       = [];

            $selectTable     = Method::post('table');
            $joinTypes       = Method::post('joinTypes');
            $viewColumns     = Method::post('viewColumns');
            $joinTables      = Arrays::deleteElement(Method::post('joinTables'), 'none');
            $joinColumns     = Arrays::deleteElement(Method::post('joinColumns'), 'none');
            $joinOtherTables = Arrays::deleteElement(Method::post('joinOtherTables'), 'none');
            $joinOtherColumns= Arrays::deleteElement(Method::post('joinOtherColumns'), 'none');

            Session::insert('gridSelectTable', $selectTable);

            if( ! empty($joinTables) )
            {
                foreach( $joinOtherTables as $key => $table )
                {
                    $searching  = array_merge($searching, DB::get($joinTables[$key])->columns(), DB::get($table)->columns());
                    $joinings[] = [$joinTables[$key] . '.' . $joinColumns[$key], $table . '.' . $joinOtherColumns[$key], $joinTypes[$key]];
                }
            }
        }

        $get     = DB::get($selectTable);
        $columns = $get->columns();

        DBGrid::limit(DASHBOARD_CONFIG['limits']['grid']);

        if( ! empty($joinings) )
        {
            Session::insert('joinings', $joinings);
            Session::insert('searching', $searching);

            DBGrid::joins(...$joinings);
            $searchValues  = $searching;
        }
        else
        {
            $searchValues  = $columns;
        }

        DBGrid::search(...$searchValues);

        foreach( $get->columnData() as $col )
        {
            if( $col->primaryKey === 1 )
            {
                DBGrid::processColumn($col->name ?? 'id');
            }
        }

        if( ! empty($viewColumns) )
        {
            Session::insert('viewColumns', $viewColumns);
            DBGrid::columns($viewColumns);
        }

        $path = FILES_DIR . 'Grids';

        Folder::create($path);

        $saves = Folder::files($path);

        $pdata['table']        = DBGrid::create($selectTable);
        $pdata['selectTable']  = $selectTable;
        $pdata['viewColumns']  = $viewColumns;
        $pdata['columns']      = Arrays::combine($columns, $columns);
        $pdata['joinCollapse'] = $joinCollapse;
        $pdata['saves']        = Arrays::combine($saves, $saves);

        Masterpage::page('grid');

        Masterpage::pdata($pdata);
    }

    /**
     * Ajax Grid Get Columns
     */
    public function gridGetColumnsAjax()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $table   = Method::post('table');
        $type    = Method::post('type');
        $columns = ['none'];

        if( $table !== 'none' )
        {
            $columns = DB::get($table)->columns();
        }

        $str = '<label>' . LANG['column'] . '</label>';
        $str .= Form::class('form-control')->onchange('changeSelected(this)')->select($type === 'join1' ? 'joinColumns[]' : 'joinOtherColumns[]', Arrays::combine($columns, $columns));

        echo $str;
    }

    /**
     * Ajax Save Grid
     */
    public function gridSaveAjax()
    {   
        if( ! Http::isAjax() )
        {
            return false;
        }

        $content  = Method::post('content');
        $saveName = Method::post('saveName');

        if( empty($saveName) )
        {
            $saveName = 'unnamed';
        }

        $path = FILES_DIR . 'Grids' . DS;

        File::write($path . $saveName, Security::htmlDecode($content));

        $this->grid();
    }

    /**
     * Ajax Load Grid
     */
    public function gridLoadAjax()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $saves = Method::post('saves');

        $path = FILES_DIR . 'Grids/' . $saves;

        if( File::exists($path) )
        {
            echo File::read($path);
        }
    }

    /**
     * Ajax Delete Grid
     */
    public function gridDeleteAjax()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $delete = Method::post('delete');

        $path = FILES_DIR . 'Grids/' . $delete;

        if( File::exists($path) )
        {
            File::delete(FILES_DIR . 'Grids/' . $delete);

            $this->grid();
        }
    }

    /**
     * Info Page
     */
    public function info(String $params = NULL)
    {
        if( ZN::$projectType === 'EIP' )
        {   
            if( Method::post('upgrade') )
            { 
                $open   = popen('composer update 2>&1', 'r');
                $result = fread($open, 8096);
                pclose($open);

                if( empty($result) )
                {
                    Masterpage::error(LANG['notUpgrade']);
                }
                else
                {
                    if( ZN_VERSION === LASTEST_VERSION )
                    {
                        Masterpage::error(LANG['alreadyVersion']);           
                    }
                    else
                    {
                        File::replace(DIRECTORY_INDEX, ZN_VERSION, LASTEST_VERSION);
                        Redirect::location(URL::current(), 0, ['success' => LANG['successUpgrade']]);
                    }
                }
            }
        }
        else
        {   
            if( Method::post('upgrade') )
            {
                if( ! empty(ZN::upgrade()) )
                {
                    Redirect::location(URL::current(), 0, ['success' => LANG['successUpgrade']]);
                }
                else
                {
                    Masterpage::error(LANG['alreadyVersion']);

                    if( $upgradeError = ZN::upgradeError() )
                    {
                        Masterpage::error($upgradeError);
                    }         
                }
            }

            View::upgrades(ZN::upgradeFiles());
        } 

        Masterpage::page('info');
    }

    /**
     * Log Page
     */
    public function log(String $params = NULL)
    {
        $project = SELECT_PROJECT;
        $path    = PROJECTS_DIR . $project . DS . 'Storage/Logs/';
        $files   = Folder::files($path, 'log');

        if( empty($files) )
        {
            Masterpage::error(LANG['notFound']);
        }

        $pdata['files'] = $files;
        $pdata['path']  = $path;
        
        Masterpage::page('logs');

        Masterpage::pdata($pdata);
    }

    /**
     * Terminal Page
     */
    public function terminal(String $params = NULL)
    {
        $pdata['supportCommands'] =
        [
            '<b>command-list</b> : ' . LANG['allCommandList']
        ];

        Masterpage::page('terminal');

        Masterpage::pdata($pdata);
    }

    /**
     * Ajax Terminal
     */
    public function terminalAjax()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        $command          = Method::post('command');
        $previousCommands = NULL;

        if( $command === 'clear' )
        {
            Session::delete('commands');
            echo ''; exit;
        }

        if( $getCommands = Session::select('commands') )
        {
            Session::insert('commands', Arrays::addLast($getCommands, $command));
        }
        else
        {
            Session::insert('commands', [$command]);
        }

        exec(Config::get('Services', 'processor')['path'] . ' zerocore project-name ' . SELECT_PROJECT. ' '.$command.' 2>&1', $response);

        $string = NULL;

        foreach( $response as $val )
        {
            $string .= $val . EOL;
        }

        echo $string;
    }

    /**
     * Ajax Back Data
     */
    public function backDataAjax()
    {
        if( ! Http::isAjax() )
        {
            return false;
        }

        echo Json::encode(Session::select('commands'));
    }

    /**
     * Backup Page
     */
    public function backup(String $params = NULL)
    {
        $project = SELECT_PROJECT;
        $path    = STORAGE_DIR . 'ProjectBackup' . DS;

        if( ! Folder::exists($path) )
        {
            Folder::create($path);
        }

        if( Method::post('backup') )
        {
            $fix      = '-' .\Date::convert(\Date::current() . \Time::current(), 'Y-m-d-H-i-s');
            $project  = $project . $fix;
            $fullPath = $path . $project;

            $databaseConfigPath = SELECT_PROJECT_DIR . 'Config' . DS . 'Database.php';

            if( Method::post('databaseBackup') )
            {
                \DBTool::backup('*', 'db.sql', $fullPath);
            }

            Folder::copy(rtrim(SELECT_PROJECT_DIR, DS), $fullPath);

            Redirect::location(URI::current(), 0, ['success' => LANG['success']]);
        }

        $files = Folder::files($path, 'dir');

        if( empty($files) )
        {
            Masterpage::error(LANG['notFound']);
        }

        $pdata['files'] = $files;
        $pdata['path']  = $path;

        Masterpage::page('backup');

        Masterpage::pdata($pdata);
    }

    /**
     * Protected Convert Create Database
     */
    protected function _ccreateDatabase(&$replace)
    {
        $query  = '^create\s+database\s+';

        if( preg_match('/' . $query . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');
            $syntax  = '/'.$query.'(\w+)/si';
            $replace = preg_replace($syntax, 'DBForge::createDatabase(\'$1\')', $replace);
        }
    }

    /**
     * Protected Convert Drop Database
     */
    protected function _cdropDatabase(&$replace)
    {
        $query  = '^drop\s+database\s+';

        if( preg_match('/' . $query . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');
            $syntax  = '/'.$query.'(\w+)/si';
            $replace = preg_replace($syntax, 'DBForge::dropDatabase(\'$1\')', $replace);
        }
    }

    /**
     * Protected Convert Drop Table
     */
    protected function _cdropTable(&$replace)
    {
        $query  = '^drop\s+table\s+';

        if( preg_match('/' . $query . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');
            $syntax  = '/'.$query.'(\w+)/si';
            $replace = preg_replace($syntax, 'DBForge::dropTable(\'$1\')', $replace);
        }
    }

    /**
     * Protected Convert Create Table
     */
    protected function _ccreateTable(&$replace)
    {
        $query  = '^create\s+table\s+';

        if( preg_match('/' . $query . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');
            $syntax  = '/'.$query.'(.*?)\s*\((.*)\)/si';

            preg_match($syntax, $replace, $match);

            $columns = explode(',', $match[2] ?? NULL);
            $options = '[';

            foreach( $columns as $val )
            {
                $val = trim($val);

                $valEx  = explode(' ', $val);
                $column = $valEx[0] ?? NULL;

                $options .= Base::presuffix(trim($column), '\'') . ' => ' . Base::presuffix(trim(str_replace($column, '', $val)), '\'') . ', ';
            }

            $options = rtrim($options, ', ');
            $options .= ']';
            $replace = preg_replace($syntax, 'DBForge::createTable(\'$1\', '.$options.')', $replace);
        }
    }

    /**
     * Protected Convert Update
     */
    protected function _cupdate(&$replace)
    {
        $update = '^update\s+';

        if( preg_match('/' . $update . '/i', $replace))
        {
            $replaceEx   = explode('where', $replace);
            $whereClause = $replaceEx[1] ?? NULL;
            $replace     = Base::suffix($replaceEx[0], ';');
            $syntax      = '/'.$update.'(.*?)\s+set\s+(.*?)(\s+|\;)$/si';

            preg_match($syntax, $replace, $match);

            if( $whereClause )
            {
                $where = preg_replace('/(\w+)\s+(\W+)\s+(.*?)\;/si', 'where(\'$1 $2\', \'$3\')->', $whereClause.';');
            }

            $columns = explode(',', $match[2] ?? NULL);
            $options = '[';

            foreach( $columns as $val )
            {
                $valEx = explode('=', trim($val));

                $options .= Base::presuffix(trim($valEx[0]), '\'') . ' => ' . trim($valEx[1]) . ', ';
            }

            $options  = rtrim($options, ', ');
            $options .= ']';
            $replace  = preg_replace($syntax, 'DB::' . trim($where) . 'update(\'$1\', '.$options.')', $replace);
        }
    }

    /**
     * Protected Convert Insert
     */
    protected function _cinsert(&$replace)
    {
        $insert = '^insert\s+';

        if( preg_match('/' . $insert . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');
            $syntax  = '/'.$insert.'into\s+(.*?)\s*\((.*?)\)\s+values\s*\((.*?)\)/si';

            preg_match($syntax, $replace, $match);

            $columns = explode(',', $match[2] ?? NULL);
            $values  = explode(',', $match[3] ?? NULL);
            $options = '[';

            foreach( $columns as $key => $val )
            {
                $options .= Base::presuffix(trim($val), '\'') . ' => ' . trim($values[$key]) . ', ';
            }

            $options  = rtrim($options, ', ');
            $options .= ']';
            $replace  = preg_replace($syntax, 'DB::insert(\'$1\', '.$options.')', $replace);
        }
    }

    /**
     * Protected Convert Delete
     */
    protected function _cdelete(&$replace)
    {
        $delete  = '^delete\s+';

        if( preg_match('/' . $delete . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');

            $from    = 'from\s+(.*?)(\;|\s+)';
            $where   = 'where\s+(.*?)(\;|\s+)';
            $where2  = 'where\s+(.*?)\s+(.*?)\s+(.*?)(\;|\s+)';

            $data =
            [
                '/'.$delete.'/si'  => '',
                '/'.$from.'/si'    => '->delete(\'$1\')',
                '/'.$where2.'/si'  => '->where(\'$1 $2\', \'$3\')',
                '/'.$where.'/si'   => '->where(\'exp:$1\')'
            ];

            $replace = preg_replace(Arrays::keys($data), Arrays::values($data), $replace);

            $this->_last($replace, '/\-\>delete\(.*?\)/', 'DB');
        }
    }

    /**
     * Protected Convert Select
     */
    protected function _cselect(&$replace)
    {
        $select  = '^select\s+(.*?)\s+';

        if( preg_match('/' . $select . '/i', $replace))
        {
            $replace = Base::suffix($replace, ';');

            $from    = 'from\s+(.*?)(\;|\s+)';
            $where   = 'where\s+(.*?)(\;|\s+)';
            $where2  = 'where\s+(.*?)\s+(.*?)\s+(.*?)(\;|\s+)';
            $limit   = 'limit\s+([0-9]+)(\;|\s+)';
            $limit2  = 'limit\s+([0-9]+\s*\,\s*[0-9]+)(\;|\s+)';
            $orderBy = 'order\s+by\s+(.*?)\s+(asc|desc)(\;|\s+)';
            $groupBy = 'group\s+by\s+(\w+)(\;|\s+)';

            $data =
            [
                '/'.$select.'/si'  => '->select(\'$1\')',
                '/'.$from.'/si'    => '->get(\'$1\')',
                '/'.$where2.'/si'  => '->where(\'$1 $2\', \'$3\')',
                '/'.$where.'/si'   => '->where(\'exp:$1\')',
                '/'.$limit2.'/si'  => '->limit($1)',
                '/'.$limit.'/si'   => '->limit($1)',
                '/'.$orderBy.'/si' => '->orderBy(\'$1\', \'$2\')',
                '/'.$groupBy.'/si' => '->groupBy(\'$1\')',
            ];

            $replace = preg_replace(Arrays::keys($data), Arrays::values($data), $replace);

            $replace = str_replace('->select(\'*\')', '', $replace);

            $this->_last($replace, '/\-\>get\(.*?\)/', 'DB');
        }
    }

    /**
     * Protected Last
     */
    protected function _last(&$replace, $getRegex, $class)
    {
        preg_match($getRegex, $replace, $match);

        $get     = $match[0] ?? NULL;
        $replace = $class . preg_replace($getRegex, '', $replace) . $get;
        $replace = str_replace('DB->', 'DB::', $replace);
    }
}
