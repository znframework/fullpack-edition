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

class Helper extends UnitTest
{
    public function _report()
    {
        $this->compare(false, $this->report('MyReport', 'My Report Content'));
    }

    public function _report2()
    {
        \ZN\Config::set('Project', 'log', ['createFile' => true]);

        $this->compare(true, $this->report('MyReport', 'My Report Content'));
    }

    public function _highLight()
    {
        $this->compare($return = $this->highLight('<?php echo 1;'), $return);
    }

    public function _toConstant()
    {
        $this->compare(MB_CASE_TITLE, $this->toConstant('title', 'MB_CASE_'));
    }

    public function _toConstant2()
    {
        $this->compare(PHP_VERSION, $this->toConstant('php', NULL, '_VERSION'));
    }
}
