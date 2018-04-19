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
     * @param int      $id
     * @param callable $callable
     * @param int      $decrement = 1
     */
    public function queue(Int $id, Callable $callable, Int $decrement = 1);

    /**
     * Crontab Run Limit
     * 
     * @param int $id
     * @param int $limit = 1
     */
    public function limit(Int $id, Int $limit = 1);

    /**
     * Selects project
     * 
     * @param string $name
     * 
     * @return Job
     */
    public function driver(String $driver) : Job;

    /**
     * Gets crontab list array
     * 
     * @return array
     */
    public function listArray() : Array;

    /**
     * Gets crontab list
     * 
     * @return string
     */
    public function list() : String;

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
    public function run(String $cmd = NULL);

    /**
     * Debug status
     * 
     * @param bool $status = true
     * 
     * @return Job
     */
    public function debug(Bool $status) : Job;

    /**
     * Cron Controller
     * 
     * @param string $file
     */
    public function controller(String $file);

    /**
     * Cron Command
     * 
     * @param string $file
     * @param string $type = 'Project' - options[Project|External]
     */
    public function command(String $file);

    /**
     * Cron wget
     * 
     * @param string $url
     */
    public function wget(String $url);

    /**
     * Path
     * 
     * @param string $path = NULL
     * 
     * @return Job
     */
    public function path(String $path = NULL);
}
