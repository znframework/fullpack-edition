<?php namespace ZN\Remote;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Ability\Container;

/**
 * @codeCoverageIgnore
 */
class Remote
{
    use Container;

    /**
     * Magic constructor
     * 
     * @param RemoteInterface $remote
     */
    public function __construct(RemoteInterface $remote = NULL)
    {
        self::$container = $remote;
    }
}
