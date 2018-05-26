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

class Parser extends UnitTest
{
    protected $data = '<media id="1">
<video id="2">Video</video>
<music id="3">
    <video id="2">Video</video>
    <video id="2">Video</video>
    <video id="2">Video</video>
</music>
</media>';

    public function _do()
    {
        $result = \ZN\XML\Parser::do($this->data);

        if( empty($result) )
        {
            $return = false;
        }
        else
        {
            $return = true;
        }
        
        $this->compare(true, $return);
    }

}
