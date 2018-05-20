<?php namespace ZN\Tests\Routing;
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

class FilterProperties extends UnitTest
{
    public function _restore()
    {
        $this->compare($class = $this->restore('127.0.0.1'), $class);
    }

    public function _usable()
    {
        $this->compare($class = $this->usable(), $class);
    }

    public function _csrf()
    {
        $this->compare($class = $this->csrf(), $class);
    }

    public function _ajax()
    {
        $this->compare($class = $this->ajax(), $class);
    }

    public function _curl()
    {
        $this->compare($class = $this->curl(), $class);
    }

    public function _restful()
    {
        $this->compare($class = $this->restful(), $class);
    }

    public function _callback()
    {
        $this->compare($class = $this->callback(function(){}), $class);
    }

    public function _method()
    {
        $this->compare($class = $this->method('post', 'get'), $class);
    }

    public function _redirect()
    {
        $this->compare($class = $this->redirect('Home'), $class);
    }
}
