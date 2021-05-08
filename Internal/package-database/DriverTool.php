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

use stdClass;
use ZN\Base;

class DriverTool extends DriverExtends
{
    /**
     * List Databases
     * 
     * @return array
     */
    public function listDatabases($query = 'SHOW DATABASES')
    {
        return $this->runListQuery($query);
    }

    /**
     * List Tables
     * 
     * @return array
     */
    public function listTables($query = 'SHOW TABLES')
    {
        return $this->runListQuery($query);
    }

    /**
     * Protected List
     */
    protected function runListQuery($query)
    {
        $result = $this->differentConnection->query($query)->result();

        if( empty($result) )
        {
            return [];
        }

        $newTables = [];

        foreach( $result as $tables )
        {
            foreach( $tables as $tb => $table )
            {
                $newTables[] = $table;
            }
        }

        return $newTables;
    }

    /**
     * Status Tabkes
     * 
     * @param mixed  $table
     * 
     * @return object
     */
    public function statusTables($table)
    {
        $infos = new stdClass;

        if( $table === '*' )
        {
            $listTables = $this->listTables();

            foreach( $listTables as $table )
            {
                $infos->$table = $this->differentConnection->status($table)->row(); // @codeCoverageIgnore
            }
        }
        elseif( is_array($table) )
        {
            foreach( $table as $tbl )
            {
                $infos->$tbl = $this->differentConnection->status($tbl)->row();
            }
        }
        else
        {
            $infos = $this->differentConnection->status($table)->row();
        }

        return $infos;
    }

    /**
     * Optimize Tabkes
     * 
     * @param mixed  $table
     * 
     * @return string
     */
    public function optimizeTables($table)
    {
        return $this->repairTables($table, 'OPTIMIZE TABLE', 'optimizeTablesSuccess');
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
    public function repairTables($table, $query = 'REPAIR TABLE', $message = 'repairTablesSuccess')
    {
        $result = $this->differentConnection->query("SHOW TABLES")->result();
        $status = NULL;

        if( $table === '*' )
        {
            foreach( $result as $tables )
            {
                // @codeCoverageIgnoreStart
                foreach( $tables as $db => $tableName )
                {
                    $status = $this->differentConnection->query($query . ' ' . $tableName);
                }
                // @codeCoverageIgnoreEnd
            }
        }
        else
        {
            $tables = is_array($table)
                    ? $table
                    : explode(',',$table);

            foreach( $tables as $tableName )
            {
                $status = $this->differentConnection->query($query . ' ' . Properties::$prefix . $tableName);
            }
        }

        if( $status !== NULL )
        {
            return $this->getLang[$message];
        }

        return false;
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
    public function backup($tables, $fileName, $path)
    {
        if( $path === STORAGE_DIR )
        {
            $path .= 'DatabaseBackup'; // @codeCoverageIgnore
        }

        $eol = EOL;

        if( $tables === '*' )
        {
            $tables = [];

            $resultArray = $this->differentConnection->query('SHOW TABLES')->resultArray();

            foreach( $resultArray as $key => $val )
            {
                $tables[] = current($val); // @codeCoverageIgnore
            }
        }
        else
        {
            $tables = ( is_array($tables) )
                      ? $tables
                      : explode(',',$tables);
        }

        $return = NULL;

        foreach( $tables as $table )
        {
            if( ! empty(Properties::$prefix) && ! strstr($table, Properties::$prefix) )
            {
                $table = Properties::$prefix.$table; // @codeCoverageIgnore
            }

            $return .= 'DROP TABLE IF EXISTS '.$table.';';

            $fetchRow = $this->differentConnection->query('SHOW CREATE TABLE '.$table)->fetchRow();

            if( ! $fetchRow )
            {
                continue;
            }

            // @codeCoverageIgnoreStart
            $fetchResult = $this->differentConnection->query('SELECT * FROM '.$table)->result();

            $return .= $eol.$eol.$fetchRow[1].";".$eol.$eol;

            if( ! empty($fetchResult) ) foreach( $fetchResult as $row )
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';

                foreach( $row as $k => $v )
                {
                    $v = preg_replace("/\n/","\\n", $v );

                    if ( isset($v) )
                    {
                        $return.= '"' . addslashes(stripslashes($v)) . '", ' ;
                    }
                    else
                    {
                        $return.= '"", ';
                    }
                }

                $return = rtrim(trim($return), ', ');

                $return .= ");".$eol;
            }

            $return .= $eol.$eol.$eol;

            // @codeCoverageIgnoreEnd
        }

        if( ! trim($return) )
        {
            return false;
        }

        if( empty($fileName) )
        {
            $fileName = 'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
        }

        if( ! is_dir($path) )
        {
            mkdir($path); // @codeCoverageIgnore
        }

        file_put_contents(Base::suffix($path) . $fileName, $return);

        return $this->getLang['backupTablesSuccess'];
    }

    /**
     * Import File
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function import(string $file)
    {
        if( is_file($file) )
        {   
            return $this->differentConnection->multiQuery(file_get_contents($file));
        }

        return false;
    }
}
