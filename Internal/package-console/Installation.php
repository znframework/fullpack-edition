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

/**
 * @command installation
 * @description installation
 */
class Installation
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command, $parameters)
    {   
        if( strtolower(substr(PHP_OS, 0, 3)) === 'win' )
        {
            exec("php zerocore generate-project-key && docker-compose up -d --build znframework");
        }
        else
        {
            exec("chmod -R 777 . && php zerocore generate-project-key && sudo docker-compose up -d --build znframework");    
        }

        new Result(true);
    }
}
