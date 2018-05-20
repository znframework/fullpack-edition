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

class Config extends UnitTest
{
    public function _get()
    {
        $this->compare(\ZN\In::defaultProjectKey(), $this->get('Project', 'key'));
    }

    public function _set()
    {
        $this->set('Project', 'key', 'MyData');

        $this->compare('MyData', $this->get('Project', 'key'));
    }

    public function _set2()
    {
        $this->set('Project', ['key' => 'MyData']);

        $this->compare('MyData', $this->get('Project', 'key'));
    }
}
