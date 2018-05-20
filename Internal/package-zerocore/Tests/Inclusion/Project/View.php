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

class View extends UnitTest
{
    public function _get()
    {
        $this->compare($content = $this->get('Sections/head', true), $content);
    }

    public function _get2()
    {
        $this->compare(NULL, $this->get('Sections/head'));
    }
}
