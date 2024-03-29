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
use ReflectionParameter;
use ZN\ErrorHandling\Errors;
use ZN\ErrorHandling\Exceptions;
use ZN\Inclusion\Project\View;
use ZN\Inclusion\Project\Masterpage;

class In
{
    /**
     * Keep view data
     * 
     * @var array
     */
    public static $view = [];
    
    /**
     * Keep masterpage data
     * 
     * @var array
     */
    public static $masterpage = [];

    /**
     * Changes project mode
     * 
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public static function projectMode()
    {
        # Controls project mode.
        switch( strtolower(PROJECT_MODE) )
        {
            # Publication Release Mode
            # All faults are off.
            # It is recommended to use this mode after the completion of the project.
            case 'publication' :
                error_reporting(0);
            break;
            
            # Restoration Repair Mode
            # The appearance of the faults is relative.
            case 'restoration' :
            
            # Development Development Mode
            # All faults are open.
            case 'development' :
                Exceptions::handler(); Errors::handler(PROJECT_CONFIG['errorReporting'] ?? 1);
            break;
            
            # Default output
            default: Base::trace('Invalid Application Mode! Available Options: ["development"], ["restoration"] or ["publication"]');
        }
    }

    /**
     * Invalid user requests are diverted to different pages.
     * 
     * @param string $authorizationType
     * @param bool   $comparisonType
     * 
     * @return void
     */
    public static function invalidRequest(string $authorizationType, bool $comparisonType)
    {
        # Gets the data from Config/Routing.php file.
        $routingRequestMethods = Config::get('Routing', 'requestMethods');

        // @codeCoverageIgnoreStart
        # It decides which request methods are allowed or not.
        if( $requestMethodsByType = $routingRequestMethods[$authorizationType] )
        {
            # Method names are converted to lowercase for correct comparison.
            $requestMethodsByTypeWithLowerCase = array_change_key_case($requestMethodsByType);

            # It is applied if there is a restriction on the requesting uri.
            if( ! empty($requestMethodsByCurrentURI = ($requestMethodsByTypeWithLowerCase[CURRENT_CFURI] ?? NULL)) )
            {
                # According to the comparison, it is decided whether or not the request flow will continue.
                if( Request::isMethod(...(array) $requestMethodsByCurrentURI) === $comparisonType )
                {
                    Singleton::class('ZN\Routing\Route')->redirectInvalidRequest();
                }
            }
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Creates the secret project key.
     * 
     * @param string $fix = NULL
     * 
     * @return string
     */
    public static function secretProjectKey(string $fix = NULL) : string
    {
        return hash('ripemd320', CONTAINER_PROJECT . $fix);
    }

    /**
     * Creates the default project key.
     * 
     * @param string $fix = NULL
     * 
     * @return string
     */
    public static function defaultProjectKey(string $fix = NULL) : string
    {
        return md5(Request::getBaseURL(strtolower(CONTAINER_PROJECT)) . $fix);
    }

    /**
     * Get current project.
     * 
     * @param void
     * 
     * @return string
     */
    public static function getCurrentProject() : string
    {
        if( self::isSubdomain() )
        {
            return false; // @codeCoverageIgnore
        }

        return (CURRENT_PROJECT === DEFAULT_PROJECT ? '' : Base::suffix(CURRENT_PROJECT));
    }

    /**
     * Get request uri.
     * 
     * @param void
     * 
     * @return string
     */
    public static function requestURI() : string
    {
        return (string) self::cleanInjection(self::applyRouteOnURI(rtrim(Request::getActiveURI(), '/')));
    }

    /**
     * Clean uri prefix
     * 
     * @param string $uri       = NULL
     * @param string $cleanData = NULL
     * 
     * @return string
     */
    public static function cleanURIPrefix(string $uri = NULL, string $cleanData = NULL) : string
    {
        $uri = $uri ?? ''; $cleanData = $cleanData ?? '';

        $suffixData = Base::suffix($cleanData);

        if( ! empty($cleanData) && stripos($uri, $suffixData) === 0 )
        {
            $uri = substr($uri, strlen($suffixData));
        }

        return $uri;
    }

    /**
     * Clears the URI from the injection.
     * 
     * @param string $string = NULL
     * 
     * @return string
     */
    public static function cleanInjection(string $string = NULL) : string
    {
        $urlInjectionChangeChars = Config::get('Security', 'urlChangeChars') ?: [];

        return str_ireplace(array_keys($urlInjectionChangeChars), array_values($urlInjectionChangeChars), $string ?? '');
    }

    /**
     * Get benchmark report table
     * 
     * @param void
     * 
     * @return void
     */
    public static function benchmarkReport()
    {
        if( Config::get('Project', 'benchmark') === true )
        {
            # System elapsed time calculating
            $elapsedTime = round(FINISH_BENCHMARK - START_BENCHMARK, 4);
            
            # Get memory usage
            $memoryUsage = memory_get_usage();

            # Get maximum memory usage
            $maxMemoryUsage = memory_get_peak_usage();

            # Template benchmark performance result table
            $benchmarkData =
            [
                'elapsedTime'    => $elapsedTime,
                'memoryUsage'    => $memoryUsage,
                'maxMemoryUsage' => $maxMemoryUsage
            ];

            $benchResult = Inclusion\View::use('BenchmarkTable', $benchmarkData, true, __DIR__ . '/Resources/');
            
            # Echo benchmark performance result table
            echo $benchResult;

            # Report log
            Helper::report('Benchmarking Test Result', $benchResult, 'BenchmarkTestResults');
        }
    }

    /**
     * Configures the startup controller settings.
     * 
     * @param string $config
     * 
     * @return void
     */
    public static function startingConfig($config)
    {
        if( $destruct = Config::get('Starting', $config) )
        {
            if( is_string($destruct) )
            {
                self::startingController($destruct); // @codeCoverageIgnore
            }
            elseif( is_array($destruct) )
            {
                foreach( $destruct as $key => $val )
                {
                    if( is_numeric($key) )
                    {
                        self::startingController($val);
                    }
                    else
                    {
                        self::startingController($key, $val); // @codeCoverageIgnore
                    }
                }
            }
        }
    }

    /**
     * Run the startup controllers.
     * 
     * @param string $startController = NULL
     * @param array  $param           = []
     * 
     * @return bool
     */
    public static function startingController(string $startController = NULL, array $param = [])
    {
        $controllerEx = explode(':', $startController ?? '');

        $controllerPath  = $controllerEx[0] ?? '';
        $controllerFunc  = $controllerEx[1] ?? Config::get('Routing', 'openFunction') ?: 'main';
        $controllerFile  = CONTROLLERS_DIR . ($suffixExtension = Base::suffix($controllerPath, '.php'));
        $controllerClass = Datatype::divide($controllerPath, '/', -1);

        # Virtual Controller - Added[5.6.0]
        if( ! is_file($controllerFile) )
        {
            $controllerFile = EXTERNAL_CONTROLLERS_DIR . $suffixExtension; // @codeCoverageIgnore
        }

        if( is_file($controllerFile) )
        {
            if( ! class_exists($controllerClass, false) )
            {
                $controllerClass = PROJECT_CONTROLLER_NAMESPACE . $controllerClass;
            }

            Base::import($controllerFile);

            // @codeCoverageIgnoreStart
            if( ! method_exists($controllerClass, $controllerFunc) || ! (new ReflectionMethod($controllerClass, $controllerFunc))->isPublic() )
            {
                Helper::report('Error', Lang::default('ZN\CoreDefaultLanguage')::select('Error', 'callUserFuncArrayError', $controllerFunc), 'SystemCallUserFuncArrayError');

                throw new Exception('Error', 'callUserFuncArrayError', $controllerFunc);
            }
            // @codeCoverageIgnoreEnd

            $exclude = $controllerClass . '::exclude';
            $include = $controllerClass . '::include';

            // @codeCoverageIgnoreStart
            // Note: Added Control 5.2.0
            if( defined($exclude) )
            {
                if( in_array(CURRENT_CFURI, $controllerClass::exclude) || in_array(CURRENT_CONTROLLER, $controllerClass::exclude) )
                {
                    return false;
                }
            }
     
            // Note: Added Control 5.2.0
            if( defined($include) )
            {
                if( ! in_array(CURRENT_CFURI, $controllerClass::include) && ! in_array(CURRENT_CONTROLLER, $controllerClass::include) )
                {
                    return false;
                }
            }
            // @codeCoverageIgnoreEnd

            # The reflection of the active controller is being taken.
            $reflector = new ReflectionClass($controllerClass);

            $startingControllerClass = Singleton::class($controllerClass);

            $return = $startingControllerClass->$controllerFunc(...(self::resolvingDependencyInjections($reflector, $controllerClass, $controllerFunc) ?: $param));

            self::$view[]       = View::$data;
            self::$masterpage[] = Masterpage::$data;
        }
        else
        {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Resolving dependency injections
     * 
     * @param ReflectionClass $reflector
     * @param string          $page
     * @param string          $function
     * 
     * [5.7.7]added
     */
    public static function resolvingDependencyInjections($reflector, $page, $function, &$getReturnType = NULL)
    {
        $getExportParameters = [];

        # If the constructor method is not available, it skips this step.
        if( $function === '__construct' && ! method_exists($page, $function) )
        {
            return $getExportParameters; // @codeCoverageIgnore
        }

        # The parameter reflection of the active controller method is being taken.
        $getReflectionParameters = ($getMethod = $reflector->getMethod($function))->getParameters();
   
        if( $getMethod->hasReturnType() )
        {
            // @codeCoverageIgnoreStart
            if( IS::phpVersion('7.4') )
            {
                $getReturnType = $getMethod->getReturnType()->getName();
            }
            else
            {
                $getReturnType = (string) $getMethod->getReturnType();
            }     
            // @codeCoverageIgnoreEnd    
        }

        # Resolving is started in case of the current match.
        foreach( $getReflectionParameters as $parameter )
        {   
            # Class and variable names are obtained.
            # [varname] for variable name.
            # [vartype] for variable type.
            if( IS::phpVersion('7.4') )
            {
                $parameterName = $parameter->getName();
                $parameterType = method_exists($getType = $parameter->getType() ?? '', 'getName') ? $getType->getName() : NULL;
                
                if( ! preg_match('/^[A-Z]/', $parameterType ?? '') )
                {
                    $parameterType = NULL;
                }
            }
            // @codeCoverageIgnoreStart
            else
            {
                preg_match
                (
                    '/<required>\s(?<vartype>([A-Z]\w+(\\\\)*){1,})\s\$(?<varname>\w+)/', 
                    ReflectionParameter::export([$page, $function], $parameter->name, true), 
                    $match
                );

                $parameterName = $match['varname'] ?? NULL;
                $parameterType = $match['vartype'] ?? NULL;
            }
            
            # If a valid class is found, the resolving continues.
            if( isset($parameterType) )
            {
                # The name of the class instance is obtained.
                $varname = $parameterName;

                # Generated instances are being sent for use in views.
                View::$varname($class = Singleton::class($parameterType));

                # The controller is creating injections of the corresponding method.
                $getExportParameters[] = $class;
            }
            // @codeCoverageIgnoreEnd
        }

        # Parameters are being sent.
        return $getExportParameters;
    }

    /**
     * Protected is subdomain
     */
    protected static function isSubdomain()
    {
        return (bool) (PROJECTS_CONFIG['directory']['others'][Base::host()] ?? false);
    }

    /**
     * All of the routes are processed.
     * 
     * @param void
     * 
     * @return void
     */
    protected static function applyRouteAll()
    {
        if( empty(ROUTES_DIR) )
        {
            return false; // @codeCoverageIgnore
        }

        $externalRouteFiles = (array) glob(EXTERNAL_ROUTES_DIR . ($fix = '*.php'));
        $routeFiles         = (array) glob(ROUTES_DIR . $fix);
        $files              = array_merge($externalRouteFiles, $routeFiles);

        if( ! empty($files)  )
        {
            Storage::start();

            foreach( $files as $file )
            {
                require $file;
            }

            Singleton::class('ZN\Routing\Route')->all();
        }
    }

    /**
     * Protected apply route on uri.
     */
    protected static function applyRouteOnURI(string $requestUri = NULL) : string
    {
        self::applyRouteAll();

        $config = Config::get('Routing');

        $uriChange   = $config['changeUri'];
        $patternType = $config['patternType'];

        if( ! empty($uriChange) ) foreach( $uriChange as $key => $val )
        {
            if( $patternType === 'classic' )
            {
                $requestUri = preg_replace(Base::presuffix($key).'xi', $val, $requestUri ?? '');
            }
            else
            {
                $requestUri = Singleton::class('ZN\Regex')->replace($key, $val, $requestUri ?? '', 'xi'); // @codeCoverageIgnore
            }
        }

        return $requestUri;
    }
}
