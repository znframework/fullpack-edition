<?php namespace ZN\Tests\Inclusion\Project;
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

class Theme extends UnitTest
{
    public function _active()
    {
        $this->compare(NULL, $this->active());
    }

    public function _integration()
    {
        $this->compare(NULL, $this->integration('Default', 'Hello Theme!'));
    }
}
