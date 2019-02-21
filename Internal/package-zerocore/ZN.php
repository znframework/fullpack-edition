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
     * Constant SE structure paths
     */
    const STRUCTURE_PATHS =
    [
        'DIRECTORY_INDEX' => 'zeroneed.php',
        'INTERNAL_DIR'    => 'Internal/',
        'EXTERNAL_DIR'    => 'External/',
        'SETTINGS_DIR'    => 'Settings/',
        'PROJECTS_DIR'    => 'Projects/',
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
    ];

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
     * Protected Lastest Version
     * 
     * @var string
     */
    protected static $lastestVersion;

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
     * Run ZN
     * 
     * @param string $type     = NULL - options[EIP|SE|CE]
     * @param string $version  = NULL
     * @param string $dedicate = NULL
     * 
     * @return void|false
     */
    public static function run(String $type = NULL, String $version = NULL, String $dedicate = NULL)
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

        # Keeps origin type.
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
        if( defined('CONSOLE_ENABLED') || $type === NULL )
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
     * Undo upgrade
     * 
     * @param string $version = 'last'
     * 
     * @return string|bool
     */
    public static function undoUpgrade($version = 'last')
    {
        $getUpgradeDirectories = Filesystem::getFiles('UpgradeBackups/', 'dir');

        $getContainerDirectory = NULL;

        if( ! empty($getUpgradeDirectories) )
        {
            if( $version === 'last' )
            {
                rsort($getUpgradeDirectories);
    
                $getContainerDirectory = $getUpgradeDirectories[0] ?? NULL;
            }
            else
            {
                $getContainerDirectory = $version;
            }
        }   

        $lastUpgradeDirectory = 'UpgradeBackups/' . $getContainerDirectory . '/';

        if( is_dir($lastUpgradeDirectory) )
        {
            Filesystem::copy($lastUpgradeDirectory, REAL_BASE_DIR); Filesystem::deleteFolder($lastUpgradeDirectory);

            return true;
        }
        else
        {
            return self::getValueLang('upgradeBackupNotFound');
        }
    }

    /**
     * Protected get value lang
     */
    protected static function getValueLang($value)
    {
        return Lang::default('ZN\CoreDefaultLanguage')::select('ZN', 'zn:' . $value);
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
            Filesystem::createFolder($upgradeFolder = 'Upgrade'.md5('upgrade').'/');
            Filesystem::createFolder($backupFolder  = 'UpgradeBackups/' . ZN_VERSION . '/');

            foreach( $return as $file => $content )
            {
                $file = $upgradeFolder . ($origin = $file);

                $dirname = pathinfo($file, PATHINFO_DIRNAME);

                Filesystem::createFolder($dirname); 
                
                if( ! empty($content) )
                {
                    # [5.7.6]added
                    # Backup upgrade files.
                    if( file_exists($origin) && ($originContent = file_get_contents($origin)) !== $content )
                    { 
                        $backupFile = $backupFolder . $origin;

                        Filesystem::createFolder(pathinfo($backupFile, PATHINFO_DIRNAME));

                        file_put_contents($backupFile, $originContent);
                    }

                    if( $origin === DIRECTORY_INDEX )
                    {
                        Filesystem::replaceData(DIRECTORY_INDEX, ZN_VERSION, self::$lastestVersion);
                    }
                    else
                    {
                        file_put_contents($file, $content);
                    }
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
     * Protected Predefined Functions
     */
    protected static function predefinedFunctions()
    {
        require __DIR__ . '/Functions.php';
    }

    /**
     * Private select structure paths
     */
    private static function selectStructurePaths()
    {
        # Project type can be specified as CE, SE, FE, EIP or NULL
        if( ! in_array(PROJECT_TYPE, ['CE', 'SE', 'EIP', NULL]) )
        {
            Base::trace('The project type can be specified as [CE], [SE], [FE], [EIP] or [NULL]. ' . PROJECT_TYPE . ' is invalid type.');
        }
        
        # Get file definitions according to the project type.
        $method = (PROJECT_TYPE ?? 'CE') . '_STRUCTURE_PATHS';

        # Get project dirs
        define('GET_DIRS', self::$method());
    }

    /**
     * Protected Predefined Constants
     */
    protected static function predefinedConstants()
    {
        # Defined Standart Constants
        # The version required for ZN Framework should be minimum 7.0.0.
        define('REQUIRED_PHP_VERSION', '7.0.0');

        # The use of SSL are automatically checked for correct operation of the system.
        # In particular, it allows the URL library to automatically detect the http prefix.
        define('SSL_STATUS', ((($_SERVER['HTTPS'] ?? NULL) === 'on' && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http') . '://');

        # The ZN Framework contains a constant namespace for the controllers.
        define('PROJECT_CONTROLLER_NAMESPACE', 'Project\Controllers\\');

        # The ZN Framework contains a constant namespace for the project commands.
        define('PROJECT_COMMANDS_NAMESPACE', 'Project\Commands\\');

        # The ZN Framework contains a constant namespace for the external commands.
        define('EXTERNAL_COMMANDS_NAMESPACE', 'External\Commands\\');

        # If the class names  contain the 'Internal' prefix, 
        # then those classes automatically create one static class.
        define('INTERNAL_ACCESS', 'Internal');

        # Predefined constants for space characters.
        define('EOL' , PHP_EOL);
        define('CRLF', "\r\n");
        define('CR'  , "\r");
        define('LF'  , "\n");
        define('HT'  , "\t");
        define('TAB' , "\t");
        define('FF'  , "\f");

        # Abbreviation for DIRECTORY_SEPARATOR.
        define('DS', DIRECTORY_SEPARATOR);
        
        # Select structure paths - options[EIP|SE|CE]
        self::selectStructurePaths();

        # The system directory is determined according to ZN project type.
        define('INTERNAL_DIR', GET_DIRS['INTERNAL_DIR']);
        define('EXTERNAL_DIR', GET_DIRS['EXTERNAL_DIR']);
        define('SETTINGS_DIR', GET_DIRS['SETTINGS_DIR']);
        define('PROJECTS_DIR', GET_DIRS['PROJECTS_DIR']);

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
        # It keeps the settings related to the structure of the projects.
        define('PROJECTS_CONFIG', Base::import(SETTINGS_DIR . 'Projects.php'));

        # Preserves project information to be run as predefined.
        # This value is set to Frontend by default.
        define('DEFAULT_PROJECT', PROJECTS_CONFIG['directory']['default']);
        
        # Defines constants to hide active project information.
        # If a request is made to a project, it will alert the screen.
        self::defineCurrentProject();

        # If there is another project covering the active project, 
        # the name of this project is obtained.
        # Otherwise the name of the project is used.
        define('CONTAINER_PROJECT', PROJECTS_CONFIG['containers'][DEFINED_CURRENT_PROJECT] ?? CURRENT_PROJECT);

        # The path information of the container project directory is stored.
        define('CONTAINER_PROJECT_DIR', PROJECTS_DIR . CONTAINER_PROJECT . '/');
        
        # The directory where the views will be created.
        define('VIEWS_DIR', PROJECT_DIR . GET_DIRS['VIEWS_DIR']);

        # The PAGES_DIR constant can be used instead of VIEWS_DIR.
        define('PAGES_DIR', VIEWS_DIR); 

        # Get Common Paths
        $externalDirectories = array_diff_key(GET_DIRS, array_flip
        ([
            'DIRECTORY_INDEX',
            'INTERNAL_DIR',
            'EXTERNAL_DIR',
            'SETTINGS_DIR',
            'PROJECTS_DIR',
            'VIEWS_DIR',
        ]));

        # Constants for both project and external directories are being created.
        foreach( $externalDirectories as $key => $value )
        {
            # Define EXTERNAL_EXAMPLE_DIR
            define('EXTERNAL_' . $key, EXTERNAL_DIR . $value);

            # For EIP edition
            if( PROJECT_TYPE === 'EIP' ) 
            {
                define($key, self::getProjectContainerDir($value));
            }
            # For SE edition
            else 
            {
                define($key, $value);
            }
        }

        if( ! is_dir(CONTROLLERS_DIR) && self::$projectType !== NULL )
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
        # Container projects are being called.
        $containers = PROJECTS_CONFIG['containers'];

        # Gets internal directories according to the active project.
        $getProjectContainerDirectory = PROJECT_DIR . $path;

        # If a project is being repaired, the project will be used with the 'Restore' prefix.
        $restoreFix = 'Restore';

        # Does the project name have a 'restore' prefix?
        $isRestorePrefix = strpos(REQUESTED_CURRENT_PROJECT, $restoreFix) === 0;

        # If the requested project is a sub-project, the project containing this project is called.
        if( ( ! empty($containers) || $isRestorePrefix ) && REQUESTED_CURRENT_PROJECT !== NULL )
        {
            # 5.3.8[added]
            # If there is a restore operation related to the desired project, 
            # the project request is reshaped according to the restore directory.
            if( $isRestorePrefix && is_dir(PROJECTS_DIR . ($restoreDirectory = ltrim(REQUESTED_CURRENT_PROJECT, $restoreFix))) )
            {
                # If the project being repaired is within the scope of another upstream project, 
                # then the project directory is enabled.
                if( $containers[$restoreDirectory] ?? NULL )
                {
                    $activeContainerDirectory = $containers[$restoreDirectory];
                }
                # Otherwise, the repaired project is enabled.
                else
                {
                    $activeContainerDirectory = $restoreDirectory;
                }
            }
            else
            {
                # If the requested project is covered by another project, that project is called.
                $activeContainerDirectory = $containers[REQUESTED_CURRENT_PROJECT] ?? NULL;
            }  
            
            # It allows common directory usage for multiple projects.
            # If the requested directory is not in the subproject, 
            # the top project directory covering this project is used in common.
            return ! empty($activeContainerDirectory) && ! is_dir($getProjectContainerDirectory)
                   ? PROJECTS_DIR . $activeContainerDirectory . '/' . $path
                   : $getProjectContainerDirectory;
        }

        # 5.3.33[edited]
        if( is_dir($getProjectContainerDirectory) )
        {
            return $getProjectContainerDirectory;
        }

        # 5.1.5[added]
        # The enclosures can be the opening controller
        if( $container = ($containers[CURRENT_PROJECT] ?? NULL) )
        {
            $getProjectContainerDirectory = str_replace(CURRENT_PROJECT, $container, $getProjectContainerDirectory);
        }

        # Return active project directory.
        return $getProjectContainerDirectory;
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
        # The .htaccess file is checked for writability. 
        # This check only applies to the apache software.
        self::isWritable('.htaccess');

        # This function is only available for EIP editions.
        if( PROJECT_TYPE !== 'EIP' )
        {
            define('CURRENT_PROJECT', NULL);
            define('PROJECT_DIR', NULL);
            define('REQUESTED_CURRENT_PROJECT', NULL);
            define('DEFINED_CURRENT_PROJECT', NULL);

            return false;
        }

        # It gets other defined subprojects.
        $getOtherDirectories = PROJECTS_CONFIG['directory']['others'];

        if( defined('CONSOLE_PROJECT_NAME') )
        {
            # Project name information from the console is kept.
            $getProjectNameFromURI = CONSOLE_PROJECT_NAME;
        }
        else
        {
            # Active URI information is being retrieved. 
            # This process is done to capture the project name.
            $currentPath = $_SERVER['PATH_INFO'] ?? $_SERVER['QUERY_STRING'] ?? false;

            # 5.0.3[edited]
            # QUERY_STRING & REQUEST URI Empty Control
            if( empty($currentPath) && ($requestUri = ($_SERVER['REQUEST_URI'] ?? false)) !== '/' )
            {
                $currentPath = $requestUri;
            }

            # 5.7.5.4[fixed]
            if( ! empty(BASE_DIR) && strpos($currentPath, BASE_DIR) === 0 )
            {
                $trimPrefix = BASE_DIR;
            }
            else
            {
                $trimPrefix = '/';
            }
            
            # Only the project name is obtained from the data.
            $getProjectNameFromURI = ( ! empty($currentPath) ? explode('/', ltrim($currentPath, $trimPrefix))[0] : '' );
        }
    
        # The host information of the page is being retrieved.
        # 5.7.5[added]
        $baseHost = Base::host();
       
        # Sub project information is being retrieved. If no alias is used, its name is used.
        # If the URI does not contain directory information, it will not get value.
        # 5.7.5[changed]
        $getProjectNameFromURI = $getOtherDirectories[$getProjectNameFromURI] ?? $getProjectNameFromURI;

        # If no subproject definition is made, the default project is activated.
        $getProjectNameFromOthers = $getOtherDirectories[$baseHost] ?? DEFAULT_PROJECT;

        # The key and value information of the other project array are flipped.
        # It is for checking the project aliases.
        $flipOthersArray = array_flip($getOtherDirectories);

        if( ! empty($getProjectNameFromURI) && is_dir(PROJECTS_DIR . $getProjectNameFromURI) )
        {
            # This keeps the project name coming from the constant URL.
            define('REQUESTED_CURRENT_PROJECT', $getProjectNameFromURI);

            $getProjectNameFromOthers = REQUESTED_CURRENT_PROJECT;

            # Project alias is checked.
            # The name of the project that comes with the URL has a valid alias name.
            $currentProjectDirectory = $flipOthersArray[$getProjectNameFromOthers] ?? $getProjectNameFromOthers;
        }
        else
        {
            # Predefined requested current project name is null.
            define('REQUESTED_CURRENT_PROJECT', NULL);
        }
        
        # The active project name is transferred to this constant.
        define('CURRENT_PROJECT', $currentProjectDirectory ?? $getProjectNameFromOthers);

        # Defines the active project directory information.
        define('DEFINED_CURRENT_PROJECT', REQUESTED_CURRENT_PROJECT ?? CURRENT_PROJECT);

        # The path information of the active project is being defined.
        define('PROJECT_DIR', Base::suffix(PROJECTS_DIR . $getProjectNameFromOthers));

        # Subdomains or different domains are prevented from opening each other.
        # 5.7.5[added]
        $getOtherDirectory = $getOtherDirectories[$baseHost] ?? NULL;

        # Gets container directory name.
        $getContainerDirectory = PROJECTS_CONFIG['containers'][$getProjectNameFromOthers] ?? NULL;
        
        # Subdomains or domains must be defined in the Settings/Projects.php 
        # configuration file in order for this control to work.
        if
        ( 
            # Only the sub projects should be opened.
            (
                $getOtherDirectory !== NULL        &&
                $getOtherDirectory !== $getProjectNameFromOthers &&
                $getOtherDirectory !== $getContainerDirectory
            )
            ||
            # The subdomain request is rejected.
            $getOtherDirectory === $getProjectNameFromURI
            ||
            # The subdomain will reject its own directory.
            DEFAULT_PROJECT === $getProjectNameFromURI
            ||
            # The subdomain can not be run outside of itself.
            ($isHost = ($flipOthersArray[$getProjectNameFromURI] ?? NULL)) && preg_match('/\w+\.\w+\.\w+/', $isHost)
        )
        {
            # All requests are directed to the home.
            defined('CONSOLE_PROJECT_NAME') ?: Response::redirect(! empty($isHost) ? Base::prefix($isHost, SSL_STATUS) : '');
        }

        # If there is no valid Projects/ directory, it returns an error.
        if( ! is_dir(PROJECT_DIR) )
        {
            Base::trace('["'.$getProjectNameFromOthers.'"] Project Directory Not Found!');
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

        self::$lastestVersion = $lastest->name;
 
        if( ZN_VERSION < self::$lastestVersion ) foreach( $return as $version )
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

    /**
     * Private CE structure paths
     */
    private static function CE_STRUCTURE_PATHS()
    {
        self::$defines['DIRECTORY_INDEX'] = self::$defines['DIRECTORY_INDEX'] ?? 'index.php';
        
        $paths = [];

        foreach( self::STRUCTURE_PATHS as $const => $path )
        {
            $paths[$const] = NULL;
        }

        return self::$defines + $paths;
    }

    /**
     * Private SE structure paths
     */
    private static function SE_STRUCTURE_PATHS()
    {
        $differents = 
        [
            'INTERNAL_DIR' => 'Libraries/',
            'EXTERNAL_DIR' => NULL,
            'SETTINGS_DIR' => 'Config/'
        ];

        return self::$defines + $differents + self::STRUCTURE_PATHS;
    }

    /**
     * Private CE structure paths
     */
    private static function EIP_STRUCTURE_PATHS()
    {
        return self::$defines + self::STRUCTURE_PATHS;
    }
}
