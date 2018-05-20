<?php namespace ZN\Tests\Inclusion;
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

class Package extends UnitTest
{
    public function _use()
    {
        $this->compare(NULL, $this->use('Default/example.css'));
    }

    public function _theme()
    {
        $this->compare(NULL, $this->theme('Default/example.css'));
    }

    public function _plugin()
    {
        $this->compare(NULL, $this->plugin('Default/example.css'));
    }
}
