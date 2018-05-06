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

use ZN\Butcher;
use ZN\Filesystem;

/**
 * @command run-butcher-delete
 * @description run-butcher-delete
 */
class RunButcherDelete
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct()
    {   
        $result = (new Butcher)->run();
        Filesystem::deleteFolder(BUTCHERY_DIR);

        new Result($result);
    }
}