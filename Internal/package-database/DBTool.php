<?php namespace ZN\Database;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class DBTool extends Connection
{
    /**
     * Database Tool Driver
     * 
     * @param object
     */
    protected $tool;

    /**
     * Magic Constructor
     * 
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        parent::__construct($settings);

        $this->tool = $this->_drvlib('Tool', $settings);
    }

    /**
     * List Databases
     * 
     * @return array
     */
    public function listDatabases()
    {
        return $this->tool->listDatabases();
    }

     /**
     * List Tables
     * 
     * @return array
     */
    public function listTables()
    {
        return $this->tool->listTables();
    }

    /**
     * Status Tabkes
     * 
     * @param mixed  $table
     * 
     * @return object
     */
    public function statusTables($table = '*')
    {
        return $this->tool->statusTables($table);
    }

    /**
     * Optimize Tabkes
     * 
     * @param mixed  $table
     * 
     * @return string
     */
    public function optimizeTables($table = '*')
    {
        return $this->tool->optimizeTables($table);
    }

    /**
     * Repair Tables
     * 
     * @param mixed  $table
     * @param string $query   = 'REPAIR TABLE'
     * @param string $message = 'repairTablesSuccess'
     * 
     * @return string
     */
    public function repairTables($table = '*')
    {
        return $this->tool->repairTables($table);
    }

    /**
     * Backup Table
     * 
     * @param mixed  $tables
     * @param string $fileName
     * @param string $path
     * 
     * @return string
     */
    public function backup($tables = '*', String $fileName = NULL, String $path = STORAGE_DIR)
    {
        return $this->tool->backup($tables, $fileName, $path);
    }

    /**
     * Import File
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function import(String $file)
    {
        return $this->tool->import($file);
    }
}
