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

class RemoteExtends
{
    /**
     * Alias different connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function new(Array $config)
    {
        return $this->differentConnection($config);
    }

    /**
     * Different Connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function differentConnection(Array $config)
    {
        $class = get_called_class();
        
        return new $class($config);
    }
}
