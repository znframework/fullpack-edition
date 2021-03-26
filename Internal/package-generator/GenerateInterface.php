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

interface GenerateInterface
{
    /**
     * Select project name
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function project(string $name) : bool;

    /**
     * Process databases
     */
    public function databases();

    /**
     * Grand Vision
     * 
     * @param mixed ...$database
     */
    public function grandVision(...$database);

    /**
     * Delete Vision
     * 
     * @param string $database = '*'
     * @param array  $tables   = NULL
     */
    public function deleteVision(string $database = '*', array $tables = NULL);

    /**
     * Settings
     * 
     * @param array $settings
     * 
     * @return Generate
     */
    public function settings(array $settings) : Generate;

    /**
     * Delete Structure
     * 
     * @param string $name
     * @param string $type = 'controller'
     * @param string $app  = NULL
     * 
     * @return bool
     */
    public function delete(string $name, string $type = 'controller', string $app = NULL) : bool;
}
