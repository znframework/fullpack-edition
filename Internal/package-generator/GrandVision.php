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

class GrandVision extends DatabaseDefinitions
{   
    /**
     * Vision Directory
     * 
     * @var string
     */
    protected $visionDirectory = 'Visions/';

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $config = Config::default('ZN\Database\DatabaseDefaultConfiguration')::get('Database', 'database');

        $this->defaultDatabaseName = $config['database'];
    }

    /**
     * Generate Grand Vision
     * 
     * @param mixed ...$database
     */
    public function generate(...$database)
    {
        $databases = $this->getDatabaseList($database);

        foreach( $databases as $connection => $database )
        {
            $this->setDatabaseConfiguration($connection, $database, $configs);

            $this->createVisionDirectoryByDatabaseName($database);

            $this->createVisionModelFile($database, $configs);
        }
    }

    /**
     * Delete Grand Vision
     * 
     * @param string $databaes = '*'
     * @param array  $tables   = NULL
     */
    public function delete(String $database = '*', Array $tables = NULL)
    {
        if( $database === '*' )
        {
            $this->deleteVisionsDirectory();
        }
        else
        {
            if( $tables === NULL )
            {
                $this->deleteVisionDirectory($database);
            }
            else
            {
                $this->deleteVisionFile($database, $tables);
            }
        }
    }

    /**
     * Protected set database configuration
     */
    protected function setDatabaseConfiguration($connection, &$database, &$configs)
    {
        $configs = [];

        if( is_array($database) )
        {
            $configs  = $database;
            $database = $connection;
        }

        $configs['database'] = $database ?: $this->defaultDatabaseName;
    }

    /**
     * Protected get database vision directory
     */
    protected function getDatabaseVisionDirectory($database)
    {
        return $this->visionDirectory . $this->getDatabaseName($database);
    }

    /**
     * Protected get database name
     */
    protected function getDatabaseName($database)
    {
        return ucfirst($database ?: $this->defaultDatabaseName);
    }
    
    /**
     * Protected create vision model file
     */
    protected function createVisionModelFile($database, $configs)
    {
        $tables = $this->getTableList($database);

        $database = ucfirst($database);

        foreach( $tables as $table )
        {
            (new File)->object
            (
                'model', 
                $this->getDatabaseVisionClassName($database, $table),
                [
                    'path'      => $this->getDatabaseVisionDirectory($database),
                    'namespace' => $this->getDatabaseVisionNamespace($database),
                    'use'       => ['GrandModel'],
                    'extends'   => 'GrandModel',
                    'constants' =>
                    [
                        'table'      => "'".ucfirst($table)."'",
                        'connection' => $this->stringArray($configs)
                    ]
                ]
            );
        }
    }

    /**
     * Protected get table list
     */
    protected function getTableList($database)
    {
        return $this->tool->differentConnection(['database' => $database])->listTables();
    }

    /**
     * Protected get database list
     */
    protected function getDatabaseList($database)
    {
        $databases = $database;

        if( is_array(($database[0] ?? NULL)) )
        {
            $databases = $database[0];
        }

        if( empty($database) )
        {
            $databases = $this->tool->listDatabases();
        }

        return $databases;
    }

    /**
     * Protected create vision diretory by database name
     */
    protected function createVisionDirectoryByDatabaseName($file)
    {
        Filesystem::createFolder(MODELS_DIR . $this->getDatabaseVisionDirectory(ucfirst($file)));
    }

    /**
     * Protected delete vision
     */
    protected function deleteVisionDirectory($vision)
    {
        Filesystem::deleteFolder($this->getVisionDirectory() . $this->getDatabaseName($vision));
    }

    /**
     * Protected delete visions
     */
    protected function deleteVisionsDirectory()
    {
        Filesystem::deleteFolder($this->getVisionDirectory());
    }

    /**
     * Protected delete vision file
     */
    protected function deleteVisionFile($database, $tables)
    {
        foreach( $tables as $table )
        {
            unlink($this->getVisionFilePath($database, $table));
        }
    }

    /**
     * Protected get visison directory
     */
    protected function getVisionDirectory()
    {
        return MODELS_DIR . $this->visionDirectory;
    }

    /**
     * Protected get database vision class name
     */
    protected function getDatabaseVisionClassName($database, $table)
    {
        return INTERNAL_ACCESS . ( strtolower($database) === strtolower($this->defaultDatabaseName) ? NULL : ucfirst($database) ) . ucfirst($table) . 'Vision';
    }

    /**
     * Protected get vision file path
     */
    protected function getVisionFilePath($database, $table)
    {
        return $this->getVisionDirectory() . Base::suffix(ucfirst($database)) . $this->getDatabaseVisionClassName($database, $table) . '.php';
    }

    /**
     * Protected get database vision namespace
     */
    protected function getDatabaseVisionNamespace($database)
    {
        return 'Visions\\' . $this->getDatabaseName($database);
    }

    /**
     * Protected String Array
     * 
     * @param array $data
     * 
     * @return string
     */
    protected function stringArray(Array $data)
    {
        $str = EOL . HT . '[' . EOL;

        foreach( $data as $key => $val )
        {
            $str .= HT . HT . "'" . $key . "' => '" . $val . "'," . EOL;
        }

        $str  = Base::removeSuffix($str, ',' . EOL);
        $str .= EOL . HT . ']';

        return $str;
    }
}
