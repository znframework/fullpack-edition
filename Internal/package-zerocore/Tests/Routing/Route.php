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

class Route extends UnitTest
{
    public function _show404()
    {
        $this->compare(NULL, $this->show404('Home'));
    }

    public function _container()
    {
        $this->compare(NULL, $this->container(function(){}));
    }

    public function _filter()
    {
        $this->compare(NULL, $this->filter());
    }

    public function _getFilters()
    {
        $this->compare($output = $this->getFilters(), $output);
    }

    public function _change()
    {
        $this->compare($output = $this->change('Homepage'), $output);
    }

    public function _uri()
    {
        $this->compare($output = $this->url('Home', true), $output);
    }

    public function _all()
    {
        $this->compare(NULL, $this->all());
    }
}
