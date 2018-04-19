<?php namespace ZN\Database\SQLite;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Database\DriverTool, Config;

class DBTool extends DriverTool
{
    /**
     * List Databases
     * 
     * @return array
     */
    public function listDatabases($query = "")
    {
        return [Config::get('Database', 'database')['database']];
    }

    /**
     * List Tables
     * 
     * @return array
     */
    public function listTables($query = "SELECT name FROM sqlite_master WHERE type = 'table'")
    {
        return $this->_list($query);
    }

    /**
     * Unsupported
     */
    public function statusTables($table)
    {
        return false;
    }

    /**
     * Unsupported
     */
    public function optimizeTables($table)
    {
        return false;
    }

    /**
     * Unsupported
     */
    public function repairTables($table, $query = '', $message = '')
    {
        return false;
    }

    /**
     * Unsupported
     */
    public function backup($tables, $fileName, $path)
    {
        return false;
    }
}