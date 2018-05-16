<?php namespace ZN\Database\SQLServer;
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
use ZN\Database\DriverTool;

class DBTool extends DriverTool
{
    /**
     * List Databases
     * 
     * @return array
     */
    public function listDatabases($query = 'SELECT name FROM master.dbo.sysdatabases')
    {
        return $this->_list($query);
    }

    /**
     * List Tables
     * 
     * @return array
     */
    public function listTables($query = "")
    {
        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='".($this->settings['database'] ?? Config::get('Database', 'database')['database'])."'";
        
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