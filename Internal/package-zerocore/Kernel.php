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

use ReflectionClass;
use ReflectionMethod;
use ZN\ErrorHandling\Errors;
use ZN\ErrorHandling\Exceptions;
use ZN\Inclusion\Project\Theme;
use ZN\Inclusion\Project\Masterpage;

class Kernel
{
    /**
     * The system initializes the kernel.
     * 
     * @param void
     * 
     * @return void
     */
    public static function start()
    {    
        # The system starts the load time.
        define('START_BENCHMARK', microtime(true));
        
        # It keeps the selected project configuration.
        define('PROJECT_CONFIG', Config::get('Project'));

        # Enables the ob_gzhandler method if it is turned on.
        define('HTACCESS_CONFIG', Config::get('Htaccess'));

        # OB process is starting.
        Buffering::start();

        # Enables route filters.
        Singleton::class('ZN\Routing\Route')->filter();

        # Session process is starting.
        Storage::start();
       
        # Sends defined header information.
        Base::headers(PROJECT_CONFIG['headers'] ?? []);

        # Activates the project mode.
        In::projectMode();

        # Sets the timezone.
        if( IS::timeZone($timezone = (PROJECT_CONFIG['timezone'] ?? '')) ) 
        {
            date_default_timezone_set($timezone);
        }
        
        # Enables defined ini configurations.
        if( $iniset = Config::get('Ini') )
        {
            Config::iniset($iniset);
        } 

        # The software apache and htaccess allow 
        # the .htaccess file to be rearranged according to the changes 
        # if the file is open for writing.
        if( IS::software() === 'apache' )
        {
            Htaccess::create();
        }    
        
        # Enables processing of changes to the robots.txt file if it is open.
        if( Config::robots('createFile') === true )
        {
            Robots::create();
        }   
        
        # Sets the system's language.
        # The lang configuration must be set to true 
        # in Config/Services.php file to enable this condition.
        if( Lang::current() )
        {
            Lang::setByURI(); // @codeCoverageIgnore
        }

        # Configures the use of Composer autoloader.
        if( $composer = Config::get('Autoloader', 'composer') ) 
        {
            Composer::loader($composer);
        }
        
        # If the setting is active, it loads the startup files.
        if( ($starting = Config::get('Starting'))['autoload']['status'] === true ) 
        {
            self::startingFileLoader($starting);
        }
        
        # If the project mode restoration is set, restoration is started.
        if( PROJECT_MODE === 'restoration' ) 
        {
            Restoration::mode();
        }
        
        # It checks for invalid requests.
        In::invalidRequest('disallowMethods', true);
        In::invalidRequest('allowMethods', false);

        # Configures the startup controller setting.
        In::startingConfig('constructors');
    }

    /**
     * Run the system kernel.
     * 
     * @param void
     * 
     * @return void
     */
    public static function run()
    {
        # The kernel is starting.
        self::start();
        
        $parameters       = CURRENT_CFPARAMETERS;
        $function         = CURRENT_CFUNCTION;
        $openFunction     = CURRENT_COPEN_PAGE;
        $page             = CURRENT_CNAMESPACE . CURRENT_CONTROLLER;
        
        // @codeCoverageIgnoreStart
        # If an invalid parameter is entered, it will redirect to the opening method.
        if( self::methodNotExists($page, $function) )
        { 
            array_unshift($parameters, $function);
            
            $function = $openFunction;
      
            # If the request is invalid, it will be redirected.
            if( self::methodNotExists($page, $function) )
            {  
                self::invalidControllerPath($page, $function);
            }            
        }
        // @codeCoverageIgnoreEnd

        # Define real current parameters.
        define('CURRENT_CPARAMETERS', $parameters);
        
        # The view path is being controlled so that the view can be loaded automatically.
        self::viewPathFinder($function, $viewPath, $wizardPath);

        # Resolving dependency injections.
        # The controller is being called.
        self::callController($page, $function, CURRENT_CPARAMETERS);
        
        # The view is automatically loading.
        self::viewAutoload($wizardPath, $viewPath);    
        
        # The operation of the system core is completes.
        self::end();
    }

    /**
     * Protected if method not exists
     * 
     * @codeCoverageIgnore
     */
    protected static function methodNotExists($page, $function)
    {
        return ! method_exists($page, $function) || ! (new ReflectionMethod($page, $function))->isPublic();
    }

    /**
     * Protected invalid controller path
     * 
     * @codeCoverageIgnore
     */
    protected static function invalidControllerPath(&$controller, &$function)
    {
        # Run direct show 404 page.
        if( $runWithoutRedirect = Config::get('Routing', 'runWithoutRedirect') )
        {
            $controllerAndMethod = explode('/', $runWithoutRedirect);

            if( ! empty($controllerAndMethod[0]) )
            {
                $info = Autoloader::getClassFileInfo(PROJECT_CONTROLLER_NAMESPACE . $controllerAndMethod[0]);
    
                define('RUN_WITHOUT_REDIRECT_CONTROLLER', pathinfo($info['path'], PATHINFO_FILENAME));

                $controller = PROJECT_CONTROLLER_NAMESPACE . RUN_WITHOUT_REDIRECT_CONTROLLER;
            }

            if( ! empty($controllerAndMethod[1]) )
            {
                $function = $controllerAndMethod[1];
            }
        }
        # Redirect show 404 page
        else if( $show404 = Config::get('Routing', 'show404') )
        {
            Helper::report('InvalidRequest', "Invalid request made to {$controller}/{$function} page!");

            Response::redirect($show404);
        }
        # Invalid operation function
        else
        {
            throw new Exception(self::getLang('invalidOpenFunction'));  
        }     
    }

    /**
     * Protected call controller
     * 
     * [added]5.7.7
     */
    protected static function callController($page, $function, $parameters)
    {
        # The reflection of the active controller is being taken.
        $reflector = new ReflectionClass($page);

        # The active controller's construct method is being resolved.
        $controller = new $page(...In::resolvingDependencyInjections($reflector, $page, '__construct'));
        
        # The active controller's current method is being resolved.
        $parameters = (In::resolvingDependencyInjections($reflector, $page, $function, $getReturnType) ?: $parameters);

        # If the return value of the controller method is set to void, 
        # ajax control is performed.
        if( $getReturnType === 'void' )
        {
            Request::isAjax() or exit; // @codeCoverageIgnore
        }

        # The parameters of the active controller method are being resolved.
        $controller->$function(...$parameters);
    }

    /**
     * View path finder
     * 
     * @param string $function
     * @param string &$viewpath
     * @param string &$wizardPath
     * 
     * @return void
     */
    public static function viewPathFinder($function, &$viewPath, &$wizardPath)
    {
        # Retrieves the automatic upload setting from the configuration file.
        $viewNameType = Config::get('Starting', 'viewNameType') ?: 'directory';
        
        # The automatic load type: file
        if( $viewNameType === 'file' )
        {
            # Setting the automatic view upload type in the file standard.
            $viewFunction = $function === CURRENT_COPEN_PAGE ? NULL : '-' . $function; // @codeCoverageIgnore

            # Views/controller-method.php
            $viewDir = self::viewPathCreator($viewFunction); // @codeCoverageIgnore
        }
        # The automatic load type: directory
        else
        {
            # Setting the automatic view upload type in the directory standard.
            $viewFunction = $function === CURRENT_COPEN_PAGE ? CURRENT_COPEN_PAGE : $function;

            # Views/controller/method.php
            $viewDir = self::viewPathCreator('/' . $viewFunction);
        }

        $viewPath   = $viewDir . '.php';
        $wizardPath = $viewDir . '.wizard.php';
    }

    /**
     * Autoload view.
     * 
     * @param string $wizardPath
     * @param string $viewPath
     * 
     * @return void
     */
    public static function viewAutoload($wizardPath, $viewPath)
    {
        # 5.3.62[added]|5.3.77|5.6.0[edited]
        if( Config::get('Starting', 'ajaxCodeContinue') === false && Request::isAjax() )
        {
            return; // @codeCoverageIgnore
        }

        # Loading can not be performed because the appropriate view page is not found.
        $usableView = '';
        
        # It is checked whether the file to be automatically loaded has been included before.
        if( ! IS::import($viewPath) && ! IS::import($wizardPath) )
        {
            # First, it is tried to load the file with the wizard extension.
            if( is_file($wizardPath) )
            {
                $usableView = self::viewLoader($wizardPath);
            }
            // @codeCoverageIgnoreStart
            # If the file can not be loaded, the attempt is made to load the file with the standard extension.
            elseif( is_file($viewPath) )
            {
                $usableView = self::viewLoader($viewPath);
            }
            // @codeCoverageIgnoreEnd
        }

        # It is checked whether data is sent to the masterpage. 
        # If data transmission is done, the masterpage is activated.
        if( ! empty($masterpageData = In::$masterpage) )
        {
            $inData = array_merge(...$masterpageData);
        }
        else
        {
            $inData = []; // @codeCoverageIgnore
        }

        # Merge sent data.
        $data = array_merge($inData, Masterpage::$data);

        # if sent data via Masterpage. Enables MasterPage.
        if( ($data['masterpage'] ?? NULL) === true || ! empty($data) )
        {
            (new Inclusion\Masterpage)->headData($data)->bodyContent($usableView)->use($data);
        }
        // @codeCoverageIgnoreStart
        # Otherwise, it prints without using the masterpage.
        elseif( ! empty($usableView) )
        {
            echo $usableView;
        }
        // @codeCoverageIgnoreEnd
    }
    
    /** 
     * End kernel.
     * 
     * @param void
     * 
     * @return void
     */
    public static function end()
    {
        In::startingConfig('destructors');

        if( PROJECT_CONFIG['log']['createFile'] === true && $errorLast = Errors::last() )
        {
            $lang    = Lang::default('ZN\ErrorHandling\ErrorHandlingDefaultLanguage')::select('Templates');
            $message = $lang['line']   .':'.$errorLast['line'].', '.
                       $lang['file']   .':'.$errorLast['file'].', '.
                       $lang['message'].':'.$errorLast['message'];

            Helper::report('GeneralError', $message);
        }

        if( PROJECT_MODE !== 'publication' ) 
        {
            Exceptions::restore(); Errors::restore();
        }

        # The system finishes the load time.
        define('FINISH_BENCHMARK', microtime(true));

        # Creates a table that calculates the operating performance of the system. 
        # To open this table, follow the steps below.
        In::benchmarkReport();

        # The buffer is being turned off.
        Buffering::end();
    }

    /**
     * Protected get lang
     * 
     * @codeCoverageIgnore
     */
    protected static function getLang(string $type)
    {
        return Lang::default('ZN\CoreDefaultLanguage')::select('Kernel')['kernel:'.$type];
    }

    /**
     * Protected Starting File Loader
     * 
     * @param array $starting
     * 
     * @return void
     */
    protected static function startingFileLoader($starting)
    {   
        if( empty(AUTOLOAD_DIR) )
        {
            return false; // @codeCoverageIgnore
        }
        
        # It is specified whether the subfile scanning can be done or not.
        $autoloadRecursive = $starting['autoload']['recursive'];

        # The files to be automatically loaded are merged at startup.
        # External & Project Files
        $startingAutoload  = (array_merge
        (
            Filesystem::getRecursiveFiles(AUTOLOAD_DIR         , $autoloadRecursive), 
            Filesystem::getRecursiveFiles(EXTERNAL_AUTOLOAD_DIR, $autoloadRecursive)
        ));

        // @codeCoverageIgnoreStart
        # The file upload process is starting.
        if( ! empty($startingAutoload) ) foreach( $startingAutoload as $file )
        {
            if( Filesystem::getExtension($file) === 'php' )
            {
                if( is_file($file) )
                {
                    Base::import($file);
                }
            }
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Protected View Path Creator
     * 
     * 5.7.0[updated]
     * 
     * @param string $fix
     * 
     * @return string
     */
    protected static function viewPathCreator($fix)
    {
        $currentController = defined('RUN_WITHOUT_REDIRECT_CONTROLLER')
                           ? RUN_WITHOUT_REDIRECT_CONTROLLER // @codeCoverageIgnore
                           : CURRENT_CONTROLLER;

        $view = $currentController . $fix;

        # If the Theme::active() method is in effect, 
        # the path information is rearranged.
        if( ($active = Theme::$active) !== NULL )
        {
            if( is_dir(PAGES_DIR . $active) )
            {
                $view = $active . $view;
            }
        }

        return PAGES_DIR . $view;
    }

    /**
     * Protected View Loader
     * 
     * @param string $path
     * 
     * @return mixed
     */
    protected static function viewLoader($path)
    {
        return Inclusion\View::use(str_replace(PAGES_DIR, '', $path ?? ''), NULL, true);
    }
}
