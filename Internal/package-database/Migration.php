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

use ZN\Base;
use ZN\Config;
use ZN\Singleton;
use ZN\Filesystem;

class Migration implements MigrationInterface
{
    /**
     * Migrations path Models/Migrations/
     * 
     * @var string
     */
    private $path = MODELS_DIR . 'Migrations/';

    /**
     * Keeps database config
     * 
     * @var array
     */
    private $config;

    /**
     * Keeps class fix
     * 
     * @var string
     */
    private $classFix = INTERNAL_ACCESS . 'Migrate';

    /**
     * Keeps migrate table name
     * 
     * @var string
     */
    private $migrateTableName;

    /**
     * Keeps version directory path
     * 
     * @var string
     */
    private $versionDir = 'Version/';

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::get('Database');

        if( ! is_dir($this->path) )
        {
            mkdir($this->path, 0755);
        }

        $this->db = Singleton::class('ZN\Database\DB');
        $this->forge = Singleton::class('ZN\Database\DBForge');
        $this->migrateTableName = defined('static::table') ? static::table : false;

        $this->createMigrationTableIfNotExists();
    }

    /**
     * Up all migrations
     * 
     * @param string ...$migrations
     * 
     * @return bool
     */
    public function upAll(String ...$migrations) : Bool
    {
        $this->runMigrateAll('up', $migrations);

        return true;
    }

    /**
     * Down all migrations
     * 
     * @param string ...$migrations
     * 
     * @return bool
     */
    public function downAll(String ...$migrations) : Bool
    {
        $this->runMigrateAll('down', $migrations);

        return true;
    }
    
    /**
     * Create table
     * 
     * @param array $data
     * 
     * @return bool
     */
    public function createTable(Array $data) : Bool
    {
        $this->forge->createTable($this->getTableName(), $data);

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Drop table
     * 
     * @param void
     * 
     * @return bool
     */
    public function dropTable() : Bool
    {
        $this->forge->dropTable($this->getTableName());

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Add column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function addColumn(Array $column) : Bool
    {
        $this->forge->addColumn($this->getTableName(), $column);

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Drop column
     * 
     * @param mixed $column
     * 
     * @return bool
     */
    public function dropColumn($column) : Bool
    {
        $this->forge->dropColumn($this->getTableName(), $column);

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Modify column
     * 
     * @param array $column
     * 
     * @param bool
     */
    public function modifyColumn(Array $column) : Bool
    {
        $this->forge->modifyColumn($this->getTableName(), $column);

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Rename column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function renameColumn(Array $column) : Bool
    {
        $this->forge->renameColumn($this->getTableName(), $column);

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Truncate table
     * 
     * @param void
     * 
     * @return bool
     */
    public function truncate() : Bool
    {
        $this->forge->truncate($this->getTableName());

        return $this->saveActionQuery(__FUNCTION__);
    }

    /**
     * Sets migration path
     * 
     * @param string $path = NULL
     * 
     * @return Migration
     */
    public function path(String $path = NULL) : Migration
    {
        $this->path = Base::suffix($path);

        return $this;
    }

    /**
     * Create migration
     * 
     * @param string $name
     * @param int    $version = 0
     * 
     * @return bool
     */
    public function create(String $name, Int $ver = 0) : Bool
    {
        if( $version = $this->getValidVersionNumber($ver) )
        {
            $this->createVersionDirectoryIfNotExists($name);

            $file = $this->getVersionFile($name, $version);

            $name .= $version;
        }
        else
        {
            $file = $this->getWithoutVersionFile($name);
        }

        return $this->generateMigrateFileIfNotExists($name, $file);
    }

    /**
     * Delete migration
     * 
     * @param string $name
     * @param int    $version = 0
     * 
     * @return bool
     */
    public function delete(String $name, Int $ver = 0) : Bool
    {
        if( $version = $this->getValidVersionNumber($ver) )
        {
            $file = $this->getVersionFile($name, $version);

            $this->deleteAllVersionDirectoryIfExists($name, $ver);
        }
        else
        {
            $file = $this->getWithoutVersionFile($name);
        }

        return $this->deleteMigrateFileIfExists($file);
    }

    /**
     * Delete all migrations
     * 
     * @param void
     * 
     * @return bool
     */
    public function deleteAll() : Bool
    {
        if( is_dir($this->path) )
        {
            return Filesystem::deleteFolder($this->path);
        }
        else
        {
            return false;
        }
    }

    /**
     * Selects migration version
     * 
     * @param int $version = 0
     * 
     * @return object
     */
    public function version(Int $version = 0)
    {
        if( empty($this->migrateTableName) )
        {
            return false;
        }

        $name = $this->classFix.$this->getTableName();

        if( $version <= 0 )
        {
            return Singleton::class($name);
        }

        $name .= $this->getValidVersionNumber($version);

        return Singleton::class($name);
    }

    /**
     * Protected delete migrate file if exists
     */
    protected function deleteMigrateFileIfExists($file)
    {
        if( is_file($file) )
        {
            return unlink($file);
        }
        
        return false;
    }

    /**
     * Protected generate migrate file if not exists
     */
    protected function generateMigrateFileIfNotExists($name, $file)
    {
        if( ! is_file($file) )
        {
            return $this->createMigrateFile($name, $file);
        }
        
        return false;
    }

    /**
     * Protected delete all version directory if exists
     */
    protected function deleteAllVersionDirectoryIfExists($name, $version)
    {
        $getVersionDirectory = $this->getVersionDirectory($name);

        if( $version === 'all' && is_dir($getVersionDirectory) )
        {
            Filesystem::deleteFolder($getVersionDirectory);
        }
    }

    /**
     * Protected create version directory if not exists
     */
    protected function createVersionDirectoryIfNotExists($name)
    {
        if( ! is_dir($getVersionDirectory = $this->getVersionDirectory($name)) )
        {
            mkdir($getVersionDirectory);
        }
    }

    /**
     * Protected get file without version
     */
    protected function getWithoutVersionFile($name)
    {
        return $this->path . Base::suffix($name, '.php');
    }

    /**
     * Protected get version file
     */
    protected function getVersionFile($name, $version)
    {
        return $this->getVersionDirectory($name) . Base::suffix($version, '.php');
    }

    /**
     * Protected create migrate
     */
    protected function getVersionDirectory($name)
    {
        return $this->path . $name . $this->versionDir;
    }

    /**
     * Protected save action query
     */
    protected function saveActionQuery($type)
    {
        if( ! $this->forge->error() )
        {
            return $this->db->insert($this->config['migration']['table'],
            [
                'name'    => $this->getTableName(),
                'type'    => $type ?: 'noAction',
                'version' => $this->getVersionNumberFromTableName(),
                'date'    => date('Ymdhis')
            ]);
        }

        return false;
    }

    /**
     * protected create
     * 
     * @param void
     * 
     * @return void
     */
    protected function createMigrationTableIfNotExists()
    {
        $table   = $this->config['database']['prefix'] . $this->config['migration']['table'];
     
        $this->forge->createTable('IF NOT EXISTS '.$table, array
        (
            'name'    => [$this->db->varchar(512), $this->db->notNull()],
            'type'    => [$this->db->varchar(256), $this->db->notNull()],
            'version' => [$this->db->varchar(3),   $this->db->notNull()],
            'date'    => [$this->db->varchar(15),  $this->db->notNull()]
        ));
    }

    /**
     * Get table name
     * 
     * @param void
     * 
     * @return string
     */
    protected function getTableName()
    {
        $table = preg_replace('/[0-9][0-9][0-9]/', '', $this->migrateTableName);

        return str_replace($this->classFix, '', $table);
    }

    /**
     * Protected get version number from table name
     */
    protected function getVersionNumberFromTableName()
    {
        preg_match('(\w+([0-9][0-9][0-9]))', $this->migrateTableName, $match);

        return $match[1] ?? '000';
    }

    /**
     * Protected get valid version number
     */
    protected function getValidVersionNumber($numeric)
    {
        $length = strlen((string)$numeric);

        if( (int)$numeric > 999 || (int)$numeric < 0 )
        {
            return false;
        }

        switch( $length )
        {
            case 1 : $numeric = '00'.$numeric; break;
            case 2 : $numeric = '0' .$numeric; break;
        }

        if( $numeric === '000' )
        {
            return false;
        }

        return $numeric;
    }

    /**
     * Protected run migrate all
     */
    protected function runMigrateAll($type, $migrations)
    {
        foreach( $migrations as $migration )
        {
            $migration = Base::prefix($migration, 'Migrate');
        
            $migration::$type();
        }
    }

    /**
     * protected create migrate file
     * 
     * @param string $name
     * @param string $file
     * 
     * @return bool
     */
    protected function createMigrateFile(String $name, String $file) : Bool
    {
        $eol  = EOL;
        $str  = '<?php'.$eol;
        $str .= 'class '.$this->classFix.$name.' extends '.__CLASS__.$eol;
        $str .= '{'.$eol;
        $str .= "\t".'# Class/Table Name'.$eol;
        $str .= "\t".'const table = __CLASS__;'.$eol.$eol;
        $str .= "\t".'# Up'.$eol;
        $str .= "\t".'public function up()'.$eol;
        $str .= "\t".'{'.$eol;
        $str .= "\t\t".'# Default Query'.$eol;
        $str .= "\t\t".'return $this->createTable' . $eol;
        $str .= "\t\t".'(['.$eol;
        $str .= "\t\t\t".'\'id\' => [DB::int(11), DB::primaryKey(), DB::autoIncrement()]' . $eol;
        $str .= "\t\t".']);'.$eol;
        $str .= "\t".'}'.$eol.$eol;
        $str .= "\t".'# Down'.$eol;
        $str .= "\t".'public function down()'.$eol;
        $str .= "\t".'{'.$eol;
        $str .= "\t\t".'# Default Query'.$eol;
        $str .= "\t\t".'return $this->dropTable();'.$eol;
        $str .= "\t".'}'.$eol;
        $str .= '}';

        return file_put_contents($file, $str);
    }
}
