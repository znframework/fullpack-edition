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

class Coalesce extends UnitTest
{
    public function _null()
    {
        \ZN\Coalesce::null($var, 'Example Data');

        $this->compare('Example Data', $var);
    }

    public function _false()
    {
        $var = false;
        
        \ZN\Coalesce::false($var, 'Example Data');

        $this->compare('Example Data', $var);
    }

    public function _empty()
    {
        $var = '';
        
        \ZN\Coalesce::empty($var, 'Example Data');

        $this->compare('Example Data', $var);
    }

    public function _empty2()
    {
        $var = 0;
        
        \ZN\Coalesce::empty($var, 'Example Data');

        $this->compare('Example Data', $var);
    }
}
