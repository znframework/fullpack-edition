<?php namespace ZN\Routing;
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
use ZN\Lang;
use ZN\Helper;
use ZN\Kernel;
use ZN\Config;
use ZN\Request;
use ZN\Response;
use ZN\Datatype;
use ZN\Singleton;
use ZN\Request\URI;
use ZN\ErrorHandling\Errors;

class Route extends FilterProperties implements RouteInterface
{
    use PropertyCreatorTrait;

    /**
     * Run direct show 404 page
     * 
     * @var bool
     */
    protected $direct = false;

    /**
     * Keeps Container Data
     * 
     * @var bool
     */
    protected $container, $useRunMethod = false;

    /**
     * Keeps Array Data
     * 
     * @var array
     */
    protected $route = [], $routes = [], $status = [], $setFilters = [], $recursion = [], $recursionFilters = [], $allFilterKeys = [], $lines;

    /**
     * Magic Constructor
     * 
     * Get route configuration.
     * 
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->getConfig = Config::get('Routing');
    }

    /**
     * Magic Destructor
     * 
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        if( $this->useRunMethod === true && empty($this->status) )
        {
            $this->redirectShow404(CURRENT_CFUNCTION);
        }
    }

    /**
     * Run direct show 404 page
     * 
     * @return self
     */
    public function direct()
    {
        $this->direct = true;

        return $this;
    }

    /**
     * Route Show404
     * 
     * @param string $controllerAndMethod
     */
    public function show404(string $controllerAndMethod)
    {
        if( $this->direct === true )
        {
            Config::set('Routing', 'runWithoutRedirect', $controllerAndMethod);

            return;
        }

        if( empty( $this->route ) )
        {
            $s404 = '404';
            $this->change('{start}404{end}');
        }

        Config::set('Routing', 'show404', $s404 ?? $this->route);

        $this->uri($controllerAndMethod, false);
    }

    /**
     * Container
     * 
     * @param callable $callback
     */
    public function container(callable $callback)
    {
        $current = count(debug_backtrace(2));
        
        # 6.55.4.32[added] recursion container
        $recursion        = $this->recursion;
        $recursionFilters = $this->recursionFilters;

        # Calculates the number of inclusive recursion.
        # Rearranges the filters according to this calculation.
        if( count($recursion) > 1 ) for( $i = count($recursion) - 1; $i >= 0; $i-- )
        {
            if( $recursion[$i] < $current )
            {
                $this->filters = array_merge($recursionFilters[$i], $this->filters); 
            
                break;
            }
        }

        $this->recursion[]        = $current; 
        $this->recursionFilters[] = $this->filters;

        $callback(); 
    }

    /**
     * Apply Filters
     */
    public function filter()
    {
        foreach( array_unique($this->allFilterKeys) as $filter )
        {
            new Filter($filter, $this->setFilters, $this->getConfig);
        }
    }

    /**
     * Sets old URI
     * 
     * @param string $path   = NULL
     */
    public function uri(string $path = NULL)
    {
        $path = $this->setPath($path, $routeConfig);

        $container = debug_backtrace(2)[2];

        $this->setContainerFilters($container);

        $this->setFilters($path);

        $this->changeRouteURI($path, $routeConfig);
    }

    /**
     * Sets all route
     */
    public function all()
    {
        if( ! empty($this->routes) )
        {
            $config = $this->getConfig;

            Config::set('Routing', 'changeUri', array_merge($this->routes['changeUri'], $config['changeUri']));
        }
    }

    /**
     * Change URI
     * 
     * @param string $route
     * 
     * @return Route
     */
    public function change(string $route) : Route
    {
        $route        = trim($route, '/');
        $return       = true;
        $routeSegment = explode('/', $route);

        // Database Routing
        $route = $this->database($route, $routeSegment, $return);

        if( empty($return) )
        {
            $this->route = NULL;
        }
        else
        {
            $this->route = $route;
        }

        return $this;
    }

    /**
     * Redirect Show 404
     * 
     * @param string $function
     * @param string $lang
     * @param report
     * 
     * @codeCoverageIgnore
     */
    public function redirectShow404(string $function, string $lang = 'callUserFuncArrayError', string $report = 'SystemCallUserFuncArrayError')
    {
        if( ! $routeShow404 = $this->getConfig['show404'] )
        {
            Helper::report('Error', Lang::default('ZN\CoreDefaultLanguage')::select('Error', $lang, $function), $report);
            
            exit(Errors::message('Error', $lang, $function));
        }
        else
        {
            Response::redirect($routeShow404);
        }
    }

    /**
     * Protected set container filters
     */
    protected function setContainerFilters($container)
    {
        $line = $container['line'];
        $func = $container['function'];

        if( $func === 'container' )
        {
            if( isset($this->lines[$line]) )
            {
                $this->filters = array_merge($this->lines[$line], $this->filters);
            }
            else
            {
                $this->lines[$line] = $this->filters;
            }
        }
    }

    /**
     * Protected set path
     */
    protected function setPath($path, &$routeConfig)
    {
        $path = rtrim($path, '/');

        $routeConfig = $this->getConfig;

        if( ! strstr($path, '/') )
        {
            $path = Base::suffix($path) . $routeConfig['openFunction'];
        }

        return $path;
    }

    /**
     * Protected change route uri
     */
    protected function changeRouteURI($path, $routeConfig)
    {
        if( empty($this->route) )
        {
            return false;
        }

        $configPatternType = $routeConfig['patternType'];
        
        if( $configPatternType === 'classic' )
        {
            $routeString = Singleton::class('ZN\Regex')->special2classic($this->route);
        }
        // @codeCoverageIgnoreStart
        elseif( $configPatternType === 'special' )
        {
            $routeString = $this->route;
        }
        // @codeCoverageIgnoreEnd

        # 5.3.21[edited] is empty
        if( trim($routeString, '/') )
        {
            $this->routes['changeUri'][$routeString] = $this->getStringRoute($path, $this->route)[$this->route];
        }

        $this->route = NULL;
    }

    /**
     * Protected routing database
     */
    protected function database($route, $routeSegment, &$return)
    {
        return preg_replace_callback
        (
            '/\[(?<table>\w+|\.)\:(?<column>\w+|\.)(\s*\,\s*(?<separator>json|serial|separator)(\:(?<key>.*?))*)*\]/i', 
            function($match) use (&$count, &$return, $routeSegment)
            {
                $count   = array_search($match[0], $routeSegment);
                $decoder = $match['separator'] ?? NULL;
                $value   = $val = URI::segment($count + 1);
                $column  = $select = $match['column'];
                $dbClass = Singleton::class('ZN\Database\DB');

                // @codeCoverageIgnoreStart
                # Json, Serial or Separator
                if( $decoder !== NULL )
                {
                    $column .= ' like';
                    $value   = $dbClass->like($value, 'inside');
                }
                // @codeCoverageIgnoreEnd

                $return = $dbClass->select($select)->where($column, $value)->get($match['table'])->value();

                // @codeCoverageIgnoreStart
                # Json, Serial or Separator
                if( $decoder !== NULL )
                {
                    $row       = $match['key'] ?? Lang::get();
                    $rows      = $decoder::decode($return);
                    $rowsArray = $decoder::decodeArray($return);
                    $return    = $rows->$row ?? NULL;

                    # Current Lang Manipulation
                    if( $return !== $value && in_array($val, $rowsArray) )
                    {
                        $arrayTransform = array_flip($rowsArray);

                        $newRow = $arrayTransform[$val];
                        $return = $rows->$newRow;

                        Lang::set($newRow);
                    }
                }
                // @codeCoverageIgnoreEnd

                return $return;

            }, 
            $route
        );
    }

    /**
     * Protected Filter
     */
    protected function setFilters($path)
    {
        $lowerPath = strtolower($path);

        $filterKeys = array_keys($this->filters);

        $this->allFilterKeys = array_merge($this->allFilterKeys, $filterKeys);

        foreach( $filterKeys as $type ) if( isset($this->filters[$type]) )
        {
            $this->setFilters[$type . 's'][$lowerPath][$type] = $this->filters[$type];
        }

        $this->filters = [];
    }

    /**
     * Protected String Route
     */
    protected function getStringRoute($functionName, $route)
    {
        preg_match_all('/\:\w+/', $route, $match);

        $newMatch = [];

        $matchAll = $match[0] ?? [];

        foreach( $matchAll as $key => $val )
        {
            $key++; // @codeCoverageIgnore

            $newMatch[] = "$$key"; // @codeCoverageIgnore
        }

        $changeRoute = str_replace($matchAll, $newMatch, $route);
        $changeRoute = str_replace(Datatype::divide($route, '/'), $functionName, $changeRoute);
        $route       = [$route => $changeRoute];

        return $route;
    }
}
