<?php namespace ZN\Crontab;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface JobInterface
{
    /**
     * Crontab Queue
     * 
     * @param callable $callable
     * @param int      $decrement = 1
     */
    public function queue(callable $callable, int $decrement = 1);

    /**
     * Crontab limit
     * 
     * @param int $getLimit = 1
     */
    public function limit(int $getLimit = 1);

    /**
     * Selects project
     * 
     * @param string $name
     * 
     * @return Job
     */
    public function driver(string $driver) : Job;

    /**
     * Gets crontab list array
     * 
     * @return array
     */
    public function listArray() : array;

    /**
     * Gets crontab list
     * 
     * @return string
     */
    public function list() : string;

    /**
     * Remove cron job
     * 
     * @param string $key = NULL
     */
    public function remove($key = NULL);

    /**
     * Run Cron
     * 
     * @param string $cmd = NULL
     * 
     * @return int
     */
    public function run(string $cmd = NULL);

    /**
     * Get string query
     * 
     * @return string
     */
    public function stringQuery();

    /**
     * Debug status
     * 
     * @param bool $status = true
     * 
     * @return Job
     */
    public function debug(bool $status) : Job;

    /**
     * Cron Controller
     * 
     * @param string $file
     */
    public function controller(string $file);

    /**
     * Cron Command
     * 
     * @param string $file
     * @param string $type = 'Project' - options[Project|External]
     */
    public function command(string $file);

    /**
     * Script [6.8.0]
     *
     * @param string $cmd
     * 
     * @return int
     */
    public function script(string $cmd);

    /**
     * Cron wget
     * 
     * @param string $url
     */
    public function wget(string $url);

    /**
     * Path
     * 
     * @param string $path = NULL
     * 
     * @return Job
     */
    public function path(string $path = NULL);
}
