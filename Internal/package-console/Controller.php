<?php namespace ZN\Console;
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
use ZN\Structure;
use ZN\Singleton;

/**
 * @command run-controller 
 * @description run-controller controller/function/p1/p2/.../pN  
 */
class Controller
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command)
    {   
        $datas      = Structure::data($command);
        $page       = $datas['page'];
        $function   = $datas['function'] ?? 'main';
        $namespace  = $datas['namespace'];
        $parameters = $datas['parameters'];
        $file       = $datas['file'];
        $class      = $namespace . $page;

        Base::import($file);

        new Result( Singleton::class($class)->$function(...$parameters) );
    }
}