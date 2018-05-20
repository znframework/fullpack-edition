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

class Masterpage extends UnitTest
{
    public function _headData()
    {
        $this->compare($class = $this->headData(['exampleKey' => ['Example Value']]), $class);
    }

    public function _body()
    {
        $this->compare($class = $this->body('body'), $class);
    }

    public function _head()
    {
        $this->compare($class = $this->head('head'), $class);
    }

    public function _title()
    {
        $this->compare($class = $this->title('Example Title'), $class);
    }

    public function _attributes()
    {
        $this->compare($class = $this->attributes(['id' => 'exampleId']), $class);
    }

    public function _meta()
    {
        $this->compare($class = $this->meta(['name:author' => 'Ozan UYKUN']), $class);
    }

    public function _content()
    {
        $this->compare($class = $this->content(['charset' => 'en']), $class);
    }

    public function _bodyContent()
    {
        $this->compare($class = $this->bodyContent('Example Body Content'), $class);
    }
}
