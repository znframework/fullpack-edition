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

class Generate extends File implements GenerateInterface
{
    /**
     * Generate Types
     * 
     * @var array
     */
    protected $types = 
    [
        'controller',
        'library',
        'command',
        'model'
    ];

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {  
        if( in_array($method, $this->types) )
        {
            $name = $parameters[0];

            return $this->object($method, $name, $parameters[1] ?? []);
        }   
        
        throw new Exception\InvalidTypeException(NULL, implode(', ', $this->types)); 
    }

    /**
     * Select project name
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function project(string $name) : bool
    {
        return Project::generate($name);
    }

    /**
     * Process databases
     */
    public function databases()
    {
        (new Databases)->generate();
    }

    /**
     * Generate Grand Vision
     * 
     * @param mixed ...$database
     */
    public function grandVision(...$database)
    {
        (new GrandVision)->generate(...$database);
    }

    /**
     * Delete Grand Vision
     * 
     * @param string $databaes = '*'
     * @param array  $tables   = NULL
     */
    public function deleteVision(string $database = '*', array $tables = NULL)
    {
        (new GrandVision)->delete($database, $tables);
    }
}
