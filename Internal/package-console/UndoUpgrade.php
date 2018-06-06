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

use ZN\ZN;

/**
 * @command undo-upgrade
 * @description undo-upgrade [last|version-number]
 */
class UndoUpgrade
{
    protected static $lang;

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct($command)
    {   
        new Result(ZN::undoUpgrade($command ?? 'last'));
    }
}