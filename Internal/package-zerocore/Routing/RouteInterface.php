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

interface RouteInterface
{
    /**
     * Run direct show 404 page
     * 
     * @return self
     */
    public function direct();
    
    /**
     * Route Show404
     * 
     * @param string $controllerAndMethod
     */
    public function show404(string $controllerAndMethod);

    /**
     * Container
     * 
     * @param callable $callback
     */
    public function container(callable $callback);

    /**
     * Restore
     * 
     * @param string|array $ips
     * @param string       $uri = NULL
     * 
     * @return Route
     */
    public function restore($ips, string $uri = NULL);

    /**
     * CSRF
     * 
     * @param bool $usable = true
     * 
     * @return Route
     */
    public function usable(bool $usable = true);

    /**
     * CSRF
     * 
     * @param string $uri = 'post'
     * 
     * @return Route
     */
    public function CSRF(string $uri = 'post');

    /**
     * Ajax
     * 
     * @return Route
     */
    public function ajax();

    /**
     * Callback
     * 
     * @param callable $callback
     * 
     * @return Route
     */
    public function callback(callable $callback);

    /**
     * Apply Filters
     */
    public function filter();

    /**
     * Sets methods
     * 
     * @param string ...$methods
     * 
     * @return Route
     */
    public function method(String ...$methods);

    /**
     * Sets redirect
     * 
     * @param string $redirect
     * 
     * @return Route
     */
    public function redirect(string $redirect);
    
    /**
     * Sets old URI
     * 
     * @param string $path   = NULL
     */
    public function uri(string $path = NULL);

    /**
     * Sets all route
     */
    public function all();

    /**
     * Change URI
     * 
     * @param string $route
     * 
     * @return Route
     */
    public function change(string $route) : Route;

    /**
     * Redirect Show 404
     * 
     * @param string $function
     * @param string $lang
     * @param report
     */
    public function redirectShow404(string $function, string $lang = 'callUserFuncArrayError', string $report = 'SystemCallUserFuncArrayError');
}
