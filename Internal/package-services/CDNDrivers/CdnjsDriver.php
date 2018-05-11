<?php namespace ZN\Services\CDNDrivers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Services\CDNAbstract;

class CdnjsDriver extends CDNAbstract
{  
    /**
     * Api address
     * 
     * @var string
     */
    protected $address = 'https://api.cdnjs.com/libraries';
}
