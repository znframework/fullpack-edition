<?php namespace ZN\Generator;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Config;
use ZN\Filesystem;
use ZN\DataTypes\Arrays;

/**
 * @codeCoverageIgnore
 */
class Databases extends DatabaseDefinitions
{   
    /**
     * Actives Path
     * 
     * @var string
     */
    protected $activesPath  = DATABASES_DIR . 'Actives/';

    /**
     * Archives Path
     * 
     * @var string
     */
    protected $archivesPath = DATABASES_DIR . 'Archives/';

    /**
     * Info
     *
     * @var array
     */
    protected $info;

    /**
     * Process databases
     */
    public function generate()
    {
        $this->active(); $this->archive();
    }

    /**
     * Info
     * 
     * @return array
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * Protected get active database list
     */
    protected function getActiveDatabaseList()
    {
        return Filesystem::getFiles($this->activesPath, 'dir');
    }

    /**
     * Protected get archive database list
     */
    protected function getArchiveDatabaseList()
    {
        return Filesystem::getFiles($this->archivesPath, 'dir');
    }

    /**
     * Protected set active database encoding
     */
    protected function setActiveDatabaseEncoding(&$encoding)
    {
        if( stristr('pdo:mysql|mysqli', Config::get('Database', 'database')['driver']) )
        {
            $encoding = $this->db->encoding();
        }
        else
        {
            $encoding = NULL;
        }
    }

    /**
     * Protected get table key column design data
     */
    protected function getTableKeyColumnDesignData()
    {
        return [$this->db->varchar(1), $this->db->null()];
    }

    /**
     * Protected get active database directory
     */
    protected function getActiveDatabaseDirectory($database)
    {
        return $this->activesPath . $database . '/';
    }

    /**
     * Protected get table list by active database
     */
    protected function getTableListByActiveDatabase($database)
    {
        return Filesystem::getFiles($this->getActiveDatabaseDirectory($database), 'php');
    }

    /**
     * Protected get db forge different connection by database name
     */
    protected function getDBForgeDifferentConnectionByDatabaseName($database)
    {
        return $this->forge->differentConnection(['database' => $database]);
    }

    /**
     * Protected get db tool different connection by database name
     */
    protected function getDBToolDifferentConnectionByDatabaseName($database)
    {
        return $this->tool->differentConnection(['database' => $database]);
    }

    /**
     * Protected get db different connection by database name
     */
    protected function getDBDifferentConnectionByDatabaseName($database)
    {
        return $this->db->differentConnection(['database' => $database]);
    }

    /**
     * Protected get active table column schema
     */
    protected function getActiveTableColumnSchema($database, $table)
    {
        return Base::import($this->getActiveDatabaseDirectory($database) . $table);
    }

    /**
     * Protected get table name without extension
     */
    protected function getTableNameWithoutExtension($table)
    {
        return Filesystem::removeExtension($table);
    }

    /**
     * Protected get active table columns
     */
    protected function getActiveTableColumns($table, $db)
    {
        return $db->get($table)->columns();
    }

    /**
     * Protected active table key and columns
     */
    protected function getActiveTableKeyAndColumns($columns, &$tableKey, &$tableColumns)
    {
        $pregGrepArray = preg_grep('/_000/', $columns);
        $tableKey      = strtolower(current($pregGrepArray));
        $tableColumns  = Arrays\RemoveElement::element($columns, $pregGrepArray);
    }

    /**
     * Protected get current table key
     */
    protected function getCurrentTableKey($table, $schema)
    {
        return strtolower($table.'_000' . md5(json_encode($schema)));
    }

    /**
     * Protected drop column from active table
     */
    protected function dropColumnFromActiveTable($table, $key, $dbForge, &$status)
    {
        $dbForge->dropColumn($table, $key);
        $status = true;
    }

    /**
     * Protected modify column from active table
     */
    protected function modifyColumnFromActiveTable($table, $key, $val, $dbForge, &$status, $active, $current)
    {
        if( $active !== $current )
        {
            $dbForge->modifyColumn($table, [$key => $val]);
            $status = true;
        }
        else
        {
            $status = false;
        }
    }

    /**
     * Protected add column from active table
     */
    protected function addColumnFromActiveTable($table, $key, $val, $dbForge, &$status)
    {
        $dbForge->addColumn($table, [$key => $val]);
        $status = true;
    }

    /**
     * Protected Actives Databases
     */
    protected function active()
    {
        if( empty($activeDatabaseList = $this->getActiveDatabaseList()) )
        {
            return false;
        }

        $this->setActiveDatabaseEncoding($encoding);

        $status = false;

        $tableKeyColumnDesignData = $this->getTableKeyColumnDesignData();
        
        foreach( $activeDatabaseList as $database )
        {
            $this->forge->createDatabase($database, $encoding);

            if( ! empty($activeTableList = $this->getTableListByActiveDatabase($database)) )
            {
                $dbForge = $this->getDBForgeDifferentConnectionByDatabaseName($database);
                $db      = $this->getDBDifferentConnectionByDatabaseName($database);

                foreach( $activeTableList as $table )
                {
                    $activeTableColumnSchema = $this->getActiveTableColumnSchema($database, $table);

                    $activeTableColumns = $this->getActiveTableColumns($table = $this->getTableNameWithoutExtension($table), $db);

                    $this->getActiveTableKeyAndColumns($activeTableColumns, $activeTableKey, $activeTableColumns);

                    $currentTableKey = $this->getCurrentTableKey($table, $activeTableColumnSchema);

                    if( ! empty($activeTableColumns) )
                    {
                        $columnsMerge = array_merge(array_flip($activeTableColumns), $activeTableColumnSchema);

                        foreach( $columnsMerge as $key => $val )
                        {
                            if( is_numeric($val) )
                            {
                                $this->dropColumnFromActiveTable($table, $key, $dbForge, $status);
                            }
                            elseif( in_array($key, $activeTableColumns) )
                            {
                                $this->modifyColumnFromActiveTable($table, $key, $val, $dbForge, $status, $activeTableKey, $currentTableKey);
                            }
                            else
                            {
                                $this->addColumnFromActiveTable($table, $key, $val, $dbForge, $status);
                            }

                            $this->addInfo($dbForge, __FUNCTION__);
                        }

                        if( $status === true )
                        {
                            $this->addTableToArchive($database, $table);
                        }
                    }
                    else
                    {
                        $activeTableColumnSchema[$currentTableKey] = $tableKeyColumnDesignData;
                        
                        $dbForge->createTable($table, $activeTableColumnSchema);

                        $this->addInfo($dbForge, __FUNCTION__);
                    }
                }
            }
        }
    }

    /**
     * protected add info
     */
    protected function addInfo($dbForge, $type)
    {
        $this->info[$type][$dbForge->stringQuery()] = $dbForge->error() ?: 'success';
    }

    /**
     * Protected add table to archive
     */
    protected function addTableToArchive($database, $table)
    {
        $this->createArchiveDatabaseDirectoryIfNotExists($database);

        file_put_contents
        (
            $this->getArchiveTableFilePath($path = $database . '/' . $table), 
            $this->getActiveTableFileContent($path)
        );
    }

    /**
     * Protected get active table file content
     */
    protected function getActiveTableFileContent($table)
    {
        return file_get_contents($this->activesPath . $table . '.php');
    }

    /**
     * Protected get archive table file path
     */
    protected function getArchiveTableFilePath($table)
    {
        return $this->archivesPath . $table . '_' . date('YmdHis') . '.php';
    }

    /**
     * Protected create archive database directory if not exists
     */
    protected function createArchiveDatabaseDirectoryIfNotExists($database)
    {
        $path = $this->archivesPath . $database . '/';
        
        if( ! is_dir($path) )
        {
            Filesystem::createFolder($path);
        }
    }

    /**
     * Protected get archive database table list
     */
    protected function getArchiveDatabaseTableList($database)
    {
        $databasePath = $this->archivesPath . $database . '/';

        $tables   = Filesystem::getFiles($databasePath, 'php');
        $pregGrep = preg_grep("/\_[0-9]*\.php/", $tables);
        
        return Arrays\RemoveElement::element($tables, $pregGrep);
    }

    /**
     * Protected Archives Database
     */
    protected function archive()
    {
        if( $archiveDatabaseList = $this->getArchiveDatabaseList() )
        {
            foreach( $archiveDatabaseList as $database )
            {
                if( ! empty($tables = $this->getArchiveDatabaseTableList($database)) )
                {
                    $dbForge = $this->getDBForgeDifferentConnectionByDatabaseName($database);
    
                    foreach( $tables as $table )
                    {
                        $dbForge->dropTable($this->getTableNameWithoutExtension($table));

                        $this->addInfo($dbForge, __FUNCTION__);
                    }
                }
    
                $tool = $this->getDBToolDifferentConnectionByDatabaseName($database);
    
                if( empty($tool->listTables()) )
                {
                    $this->forge->dropDatabase($database);
                }
            }

            return true;
        }
        
        return false;
    }
}
