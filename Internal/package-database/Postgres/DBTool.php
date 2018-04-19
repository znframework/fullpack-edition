<?php namespace ZN\Database\Postgres;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Database\DriverTool;

class DBTool extends DriverTool
{
    /**
     * List Databases
     * 
     * @return array
     */
    public function listDatabases($query = 'SELECT datname FROM pg_database')
    {
        return $this->_list($query);
    }

    /**
     * List Tables
     * 
     * @return array
     */
    public function listTables($query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")
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
