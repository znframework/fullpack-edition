<?php namespace ZN\XML\Tests;
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

class Save extends UnitTest
{
    public function _do()
    {
        $this->compare(true, $this->do('example', 'data'));
    }
}
