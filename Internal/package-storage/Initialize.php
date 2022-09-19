<?php namespace ZN\Storage;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Datatype;

trait Initialize
{
    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct(array $config = [])
    {
        $this->config = $config ?: Config::default(__CLASS__ . 'DefaultConfiguration')
                                         ::get('Storage', strtolower(Datatype::divide(__CLASS__, '\\', -1)));

        $this->start();
    }
}
