<?php namespace ZN\Tests;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Controller\UnitTest;

class Hypertext extends UnitTest
{
    public function _attributes()
    {
        $this->compare($return = $this->attributes(['id' => 'example', 'class' => 'blue']), $return);
    }
}
