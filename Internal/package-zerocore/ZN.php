<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Filesystem;
use ZN\Services\Restful;
use ZN\Protection\Separator;
use ZN\ErrorHandling\Exceptions;

class ZN
{
    /**
     * Keeps custom defines
     * 
     * @var array
     */
    protected static $defines = [];

    /**
     * Upgrade Error
     * 
     * @var string
     */
    public static $upgradeError;

    /**
     * Project Type
     * 
     * @var string
     */
    public static $projectType;

    /**
     * Magic call static
     * 
     * @param string $class
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($class, $parameters)
    {
        return Singleton::class($class, $parameters);
    }

    /**
     * Upgrade system
     * 
     * @param void
     * 
     * @return bool
     */
    public static function upgrade()
    {
        $return = self::getRestGithubResult();

        if( ! empty($return) )
        {
            $upgradeFolder = 'Upgrade'.md5('upgrade').'/';

            Filesystem::createFolder($upgradeFolder);

            foreach( $return as $file => $content )
            {
                $file = $upgradeFolder . $file;

                $dirname = pathinfo($file, PATHINFO_DIRNAME);

                Filesystem::createFolder($dirname); 
                
                if( ! empty($content) )
                {
                    file_put_contents($file, $content);
                } 
            }

            Filesystem::copy($upgradeFolder, REAL_BASE_DIR); Filesystem::deleteFolder($upgradeFolder);

            return true;
        }

        return false;
    }

    /**
     * Get upgrade error
     * 
     * @return string
     */
    public static function upgradeError()
    {
        return self::$upgradeError ?: false;
    }

    /**
     * Get upgrade files
     * 
     * @param void
     * 
     * @return array
     */
    public static function upgradeFiles()
    {
        return array_keys(self::getRestGithubResult());
    }

    /**
     * Custom Defines
     * 
     * @param array $defines
     * 
     * @return self
     */
    public static function defines(Array $defines)
    {
        self::$defines = $defines;

        return new self;
    }

    /**
     * Run ZN
     * 
     * @param string $type     = 'EIP' - options[EIP|SE]
     * @param string $version  = NULL
     * @param string $dedicate = NULL
     * 
     * @return void|false
     */
    public static function run(String $type = 'EIP', String $version = NULL, String $dedicate = NULL)
    {
        # PHP shows code errors.
        ini_set('display_errors', true);
        
        # The system starts the load time.
        define('START_BENCHMARK', microtime(true));

        # ZN Version
        define('ZN_VERSION', $version);

        # Dedicated
        define('ZN_DEDICATE', $dedicate);

        # It shows you which framework you are using. SE for single edition, EIP for multi edition.
        define('PROJECT_TYPE', $type === 'FE' ? 'EIP' : $type);
        self::$projectType = $type;

        # Define standart constants
        self::predefinedConstants();

        # Predefined Functions
        self::predefinedFunctions();

        # Defines constants required for system and user.
        self::defineDirectoryConstants();

        # Enables class loading by automatically activating the object call.
        Autoloader::register();
        
        # Provides data about the current working url.
        Structure::defines();

        # If the operation is executed via console, the code flow is not continue.  
        if( defined('CONSOLE_ENABLED') )
        {
            return false;
        }

        # The code to be written to this layer runs before the system files are 
        # loaded. For this reason, you can not use ZN libraries.
        Base::layer('Top');

        # Enables route filters.
        Singleton::class('ZN\Routing\Route')->filter();

        # You can use system constants and libraries in this layer since the code 
        # to write to this layer is used immediately after the auto loader. 
        # All Config files can be configured on this layer since this layer runs 
        # immediately after the auto installer.
        Base::layer('TopBottom');

        # Run Kernel
        try 
        { 
            Kernel::run();  
        }
        catch( Throwable $e )
        {
            if( PROJECT_MODE !== 'publication' ) 
            {
                Exceptions::table($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
            }   
        }

        # The system finishes the load time.
        define('FINISH_BENCHMARK', microtime(true));

        # Creates a table that calculates the operating performance of the system. 
        # To open this table, follow the steps below.
        In::benchmarkReport();
    }

    /**
     * Protected Predefined Functions
     */
    protected static function predefinedFunctions()
    {
        require __DIR__ . '/Functions.php';
    }

    /**
     * Protected Predefined Constants
     */
    protected static function predefinedConstants()
    {
        # Defined Standart Constants
        define('REQUIRED_PHP_VERSION', '7.0.0');
        define('SSL_STATUS', ((($_SERVER['HTTPS'] ?? NULL) === 'on' && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http') . '://');
        define('PROJECT_CONTROLLER_NAMESPACE', 'Project\Controllers\\');
        define('PROJECT_COMMANDS_NAMESPACE' , 'Project\Commands\\');
        define('EXTERNAL_COMMANDS_NAMESPACE', 'External\Commands\\');
        define('INTERNAL_ACCESS', 'Internal');
        define('EOL', PHP_EOL);
        define('CRLF', "\r\n" );
        define('CR', "\r");
        define('LF', "\n");
        define('HT', "\t");
        define('TAB', "\t");
        define('FF', "\f");
        define('DS', DIRECTORY_SEPARATOR);

        # All project types
        define('PROJECT_TYPE_DIRS', 
        [
            'SE' => 
            [
                'INTERNAL_DIR'    => 'Libraries/',
                'EXTERNAL_DIR'    => NULL,
                'BUTCHERY_DIR'    => 'Butchery/',
                'SETTINGS_DIR'    => 'Config/',
                'DIRECTORY_INDEX' => 'zeroneed.php',
                'CONTROLLERS_DIR' => 'Controllers/',
                'VIEWS_DIR'       => 'Views/',
                'ROUTES_DIR'      => 'Routes/',
                'CONFIG_DIR'      => 'Config/',
                'DATABASES_DIR'   => 'Databases/',
                'STORAGE_DIR'     => 'Storage/',
                'COMMANDS_DIR'    => 'Commands/',
                'LANGUAGES_DIR'   => 'Languages/',
                'LIBRARIES_DIR'   => 'Libraries/',
                'MODELS_DIR'      => 'Models',
                'STARTING_DIR'    => 'Starting/',
                'AUTOLOAD_DIR'    => 'Starting/Autoload/',
                'HANDLOAD_DIR'    => 'Starting/Handload/',
                'LAYERS_DIR'      => 'Starting/Layers/',
                'RESOURCES_DIR'   => 'Resources/',
                'FILES_DIR'       => 'Resources/Files/',
                'TEMPLATES_DIR'   => 'Resources/Templates/',
                'THEMES_DIR'      => 'Resources/Themes/',
                'PLUGINS_DIR'     => 'Resources/Plugins/',
                'UPLOADS_DIR'     => 'Resources/Uploads/'
            ],

            'EIP' => 
            [
                'INTERNAL_DIR'    => 'Internal/',
                'EXTERNAL_DIR'    => 'External/',
                'SETTINGS_DIR'    => 'Settings/',
                'DIRECTORY_INDEX' => 'zeroneed.php',
                'BUTCHERY_DIR'    => 'Butchery/',
                'CONTROLLERS_DIR' => 'Controllers/',
                'VIEWS_DIR'       => 'Views/',
                'ROUTES_DIR'      => 'Routes/',
                'CONFIG_DIR'      => 'Config/',
                'DATABASES_DIR'   => 'Databases/',
                'STORAGE_DIR'     => 'Storage/',
                'COMMANDS_DIR'    => 'Commands/',
                'LANGUAGES_DIR'   => 'Languages/',
                'LIBRARIES_DIR'   => 'Libraries/',
                'MODELS_DIR'      => 'Models/',
                'STARTING_DIR'    => 'Starting/',
                'AUTOLOAD_DIR'    => 'Starting/Autoload/',
                'HANDLOAD_DIR'    => 'Starting/Handload/',
                'LAYERS_DIR'      => 'Starting/Layers/',
                'RESOURCES_DIR'   => 'Resources/',
                'FILES_DIR'       => 'Resources/Files/',
                'TEMPLATES_DIR'   => 'Resources/Templates/',
                'THEMES_DIR'      => 'Resources/Themes/',
                'PLUGINS_DIR'     => 'Resources/Plugins/',
                'UPLOADS_DIR'     => 'Resources/Uploads/'
            ],

            'CE' => 
            [
                'INTERNAL_DIR'    => self::$defines['INTERNAL_DIR']    ?? NULL,
                'EXTERNAL_DIR'    => NULL,
                'SETTINGS_DIR'    => self::$defines['SETTINGS_DIR']    ?? NULL,
                'DIRECTORY_INDEX' => self::$defines['DIRECTORY_INDEX'] ?? 'index.php',
                'BUTCHERY_DIR'    => self::$defines['BUTCHERY_DIR']    ?? NULL,
                'CONTROLLERS_DIR' => self::$defines['CONTROLLERS_DIR'] ?? NULL,
                'VIEWS_DIR'       => self::$defines['VIEWS_DIR']       ?? NULL,
                'ROUTES_DIR'      => self::$defines['ROUTES_DIR']      ?? NULL,
                'CONFIG_DIR'      => self::$defines['CONFIG_DIR']      ?? NULL,
                'DATABASES_DIR'   => self::$defines['DATABASES_DIR']   ?? NULL,
                'STORAGE_DIR'     => self::$defines['STORAGE_DIR']     ?? NULL,
                'COMMANDS_DIR'    => self::$defines['COMMANDS_DIR']    ?? NULL,
                'LANGUAGES_DIR'   => self::$defines['LANGUAGES_DIR']   ?? NULL,
                'LIBRARIES_DIR'   => self::$defines['LIBRARIES_DIR']   ?? NULL,
                'MODELS_DIR'      => self::$defines['MODELS_DIR']      ?? NULL,
                'STARTING_DIR'    => self::$defines['STARTING_DIR']    ?? NULL,
                'AUTOLOAD_DIR'    => self::$defines['AUTOLOAD_DIR']    ?? NULL,
                'HANDLOAD_DIR'    => self::$defines['HANDLOAD_DIR']    ?? NULL,
                'LAYERS_DIR'      => self::$defines['LAYERS_DIR']      ?? NULL,
                'RESOURCES_DIR'   => self::$defines['RESOURCES_DIR']   ?? NULL,
                'FILES_DIR'       => self::$defines['FILES_DIR']       ?? NULL,
                'TEMPLATES_DIR'   => self::$defines['TEMPLATES_DIR']   ?? NULL,
                'THEMES_DIR'      => self::$defines['THEMES_DIR']      ?? NULL,
                'PLUGINS_DIR'     => self::$defines['PLUGINS_DIR']     ?? NULL,
                'UPLOADS_DIR'     => self::$defines['UPLOADS_DIR']     ?? NULL,
            ]
        ]);

        # Get project dirs
        define('GET_DIRS', PROJECT_TYPE_DIRS[PROJECT_TYPE]);

        # The system directory is determined according to ZN project type.
        define('INTERNAL_DIR', GET_DIRS['INTERNAL_DIR']);
        define('EXTERNAL_DIR', GET_DIRS['EXTERNAL_DIR']);
        define('SETTINGS_DIR', GET_DIRS['SETTINGS_DIR']);
        define('PROJECTS_DIR', 'Projects/');

        # Directory Index
        define('DIRECTORY_INDEX', GET_DIRS['DIRECTORY_INDEX']);
        define('BASE_DIR', ltrim(explode(DIRECTORY_INDEX, $_SERVER['SCRIPT_NAME'])[0], '/'));

        # It keeps path of the files needed for the system.
        define('ZEROCORE', INTERNAL_DIR . 'ZN/');

        # The system gives the knowledge of the actual root directory.
        define('REAL_BASE_DIR', self::getCurrentWorkingDirectory());
    }

    /**
     * Defines required constants
     * 
     * @param string $version
     * 
     * @return void
     */
    public static function defineDirectoryConstants()
    {
        define('PROJECTS_CONFIG', Base::import(SETTINGS_DIR . 'Projects.php'));
        define('DEFAULT_PROJECT', PROJECTS_CONFIG['directory']['default']);
        
        self::defineCurrentProject();

        $currentProjectDirectory = defined('_CURRENT_PROJECT') ? _CURRENT_PROJECT : CURRENT_PROJECT;

        define('CONTAINER_PROJECT', PROJECTS_CONFIG['containers'][$currentProjectDirectory] ?? CURRENT_PROJECT);
        define('CONTAINER_PROJECT_DIR', PROJECTS_DIR . CONTAINER_PROJECT . '/');
        
        define('VIEWS_DIR'       , PROJECT_DIR . GET_DIRS['VIEWS_DIR']);
        define('PAGES_DIR'       , VIEWS_DIR); 
        define('CONTAINER_DIRS', 
        [
            'ROUTES_DIR'    => GET_DIRS['ROUTES_DIR']   , 'DATABASES_DIR'   => GET_DIRS['DATABASES_DIR']  ,
            'CONFIG_DIR'    => GET_DIRS['CONFIG_DIR']   , 'STORAGE_DIR'     => GET_DIRS['STORAGE_DIR']    ,
            'COMMANDS_DIR'  => GET_DIRS['COMMANDS_DIR'] , 'LANGUAGES_DIR'   => GET_DIRS['LANGUAGES_DIR']  ,
            'LIBRARIES_DIR' => GET_DIRS['LIBRARIES_DIR'], 'MODELS_DIR'      => GET_DIRS['MODELS_DIR']     ,
            'STARTING_DIR'  => GET_DIRS['STARTING_DIR'] , 'AUTOLOAD_DIR'    => GET_DIRS['AUTOLOAD_DIR']   ,
                                                          'HANDLOAD_DIR'    => GET_DIRS['HANDLOAD_DIR']   ,
                                                          'LAYERS_DIR'      => GET_DIRS['LAYERS_DIR']     ,
                                                          'CONTROLLERS_DIR' => GET_DIRS['CONTROLLERS_DIR'],
                                                          'BUTCHERY_DIR'    => GET_DIRS['BUTCHERY_DIR'],
            'RESOURCES_DIR' => GET_DIRS['RESOURCES_DIR'], 'FILES_DIR'       => GET_DIRS['FILES_DIR']      ,
                                                          'TEMPLATES_DIR'   => GET_DIRS['TEMPLATES_DIR']  ,
                                                          'THEMES_DIR'      => GET_DIRS['THEMES_DIR']     ,
                                                          'PLUGINS_DIR'     => GET_DIRS['PLUGINS_DIR']    ,
                                                          'UPLOADS_DIR'     => GET_DIRS['UPLOADS_DIR']    ,
        ]);

        foreach( CONTAINER_DIRS as $key => $value )
        {
            define('EXTERNAL_' . $key, EXTERNAL_DIR . $value);

            if( PROJECT_TYPE === 'EIP' ) # For EIP edition
            {
                define($key, self::getProjectContainerDir($value));
            }
            else # For SE edition
            {
                define($key, $value);
            }
        }

        if( ! is_dir(CONTROLLERS_DIR) )
        {
            Base::trace
            (
                'The [controller directory] for the custom edition must be defined. 
                To do this, specify the corresponding controller directory in the [index.php] file.'
            );
        }
    }

    /**
     * Protected Get Current Working Directory
     * 
     * @return string
     */
    protected static function getCurrentWorkingDirectory()
    {
        return (getcwd() ?: pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME)) . '/';
    }

    /**
     * Get project container directory
     * 
     * Returns the project directory name according to the project in the system.
     * Only for multi edition.
     * 
     * @param string $path = NULL
     * 
     * @return string
     */
    protected static function getProjectContainerDir($path = NULL) : String
    {
        $containers          = PROJECTS_CONFIG['containers'];
        $containerProjectDir = PROJECT_DIR . $path;

        if( ! empty($containers) && defined('_CURRENT_PROJECT') )
        {
            $restoreFix = 'Restore';

            # 5.3.8[added]
            if( strpos(_CURRENT_PROJECT, $restoreFix) === 0 && is_dir(PROJECTS_DIR . ($restoredir = ltrim(_CURRENT_PROJECT, $restoreFix))) )
            {
                $condir = $restoredir;

                if( $containers[$condir] ?? NULL )
                {
                    $condir = $containers[$condir];
                }
            }
            else
            {
                $condir = $containers[_CURRENT_PROJECT] ?? NULL;
            }  
            
            return ! empty($condir) && ! file_exists($containerProjectDir)
                    ? PROJECTS_DIR . Base::suffix($condir) . $path
                    : $containerProjectDir;
        }

        # 5.3.33[edited]
        if( is_dir($containerProjectDir) )
        {
            return $containerProjectDir;
        }

        # 5.1.5[added]
        # The enclosures can be the opening controller
        if( $container = ($containers[CURRENT_PROJECT] ?? NULL) )
        {
            $containerProjectDir = str_replace(CURRENT_PROJECT, $container, $containerProjectDir);
        }

        return $containerProjectDir;
    }

    /**
     * Define current project
     * 
     * It arranges some values according to the project which is valid in the system.
     * 
     * @param void
     * 
     * @return mixed
     */
    protected static function defineCurrentProject()
    {
        self::isWritable('.htaccess');

        if( PROJECT_TYPE !== 'EIP' )
        {
            define('CURRENT_PROJECT', NULL);
            define('PROJECT_DIR'    , NULL);

            return false;
        }

        $projectConfig = PROJECTS_CONFIG['directory']['others'];
        $projectDir    = $projectConfig;

        if( defined('CONSOLE_PROJECT_NAME') )
        {
            $internalDir = CONSOLE_PROJECT_NAME;
        }
        else
        {
            $currentPath = $_SERVER['PATH_INFO'] ?? $_SERVER['QUERY_STRING'] ?? false;

            # 5.0.3[edited]
            # QUERY_STRING & REQUEST URI Empty Control
            if( empty($currentPath) && ($requestUri = ($_SERVER['REQUEST_URI'] ?? false)) !== '/' )
            {
                $currentPath = $requestUri;
            }
            
            $internalDir = ( ! empty($currentPath) ? explode('/', ltrim($currentPath, BASE_DIR ?: '/'))[0] : '' );
        }

        if( is_array($projectDir) )
        {
            $internalDir = $projectDir[$internalDir] ?? $internalDir;
            $projectDir  = $projectDir[Base::host()] ?? DEFAULT_PROJECT;
        }

        if( ! empty($internalDir) && is_dir(PROJECTS_DIR . $internalDir) )
        {
            define('_CURRENT_PROJECT', $internalDir);

            $flip              = array_flip($projectConfig);
            $projectDir        = _CURRENT_PROJECT;
            $currentProjectDir = $flip[$projectDir] ?? $projectDir;
        }

        define('CURRENT_PROJECT', $currentProjectDir ?? $projectDir);
        define('PROJECT_DIR', Base::suffix(PROJECTS_DIR . $projectDir));

        if( ! is_dir(PROJECT_DIR) )
        {
            Base::trace('["'.$projectDir.'"] Project Directory Not Found!');
        }
    }

    /**
     * Get restful class
     * 
     * @return \ZN\Services\Restful
     */
    protected static function getRestfulClass()
    {
        return new Restful;
    }

    /**
     * protected restful
     * 
     * @param void
     * 
     * @return array
     */
    protected static function getRestGithubResult()
    {
        $restful = self::getRestfulClass();
        $return  = $restful->useragent(true)->get('https://api.github.com/repos/znframework/fullpack-edition/tags');

        $updatedFiles = [];

        if( isset($return->message) )
        {
            self::$upgradeError = $return->message;

            return $updatedFiles;
        }

        usort($return, function($data1, $data2){ return strcmp($data1->name, $data2->name); });

        rsort($return);

        $lastest = $return[0];
 
        if( ZN_VERSION < $lastest->name ) foreach( $return as $version )
        {
            if( ZN_VERSION < $version->name )
            {
                $commit = $restful->useragent(true)->get($version->commit->url);

                foreach( $commit->files as $file )
                {
                    if( ! isset($updatedFiles[$file->filename]) )
                    {
                        if( ! empty($file->raw_url) )
                        {
                            # If the changes are in the project directory, they are ignored.
                            # The only exception to this in projects is devtools. 5.7.3.7[added]
                            if( strpos($file->filename, PROJECTS_DIR) !== 0 || strpos($file->filename, PROJECTS_DIR . 'Devtools') === 0 )
                            {
                                $updatedFiles[$file->filename] = file_get_contents($file->raw_url);
                            }
                        }
                    }        
                }
            }
            else
            {
                break;
            }
        }
        
        return $updatedFiles;
    }

    /**
    * Is writable
    * 
    * Controls whether file permission is required in the operating system where the system is installed.
    * 
    * @param string $path
    * 
    * @return void
    */
    protected static function isWritable(String $path)
    {
        if( is_file($path) && ! is_writable($path) && IS::software() === 'apache' )
        {   
            Base::trace
            (
                'Please check the [file permissions]. Click the 
                    <a target="_blank" style="text-decoration:none" href="https://docs.znframework.com/getting-started/installation-instructions#sh42">
                        [documentation]
                    </a> 
                to see how to configure file permissions.'
            );
        }
    }
}
