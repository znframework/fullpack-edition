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

class Datatype extends UnitTest
{
    protected $array = 
    [
        'ExampleData' => 'Example Data'
    ];

    public function _caseArray()
    {
        $this->compare(['exampledata' => 'example data'], $this->caseArray($this->array));
    }

    public function _caseArray2()
    {
        $this->compare(['EXAMPLEDATA' => 'EXAMPLE DATA'], $this->caseArray($this->array, 'upper'));
    }

    public function _caseArray3()
    {
        $this->compare(['EXAMPLEDATA' => 'Example Data'], $this->caseArray($this->array, 'upper', 'key'));
    }

    public function _multikey()
    {
        $this->compare(['a' => 'A', 'b' => 'A'], $this->multikey(['a|b' => 'A']));
    }

    public function _divide()
    {
        $this->compare('Foo', $this->divide('Foo/Bar/Baz', '/'));
    }

    public function _divide2()
    {
        $this->compare('Bar', $this->divide('Foo/Bar/Baz', '/', 1));
    }

    public function _divide3()
    {
        $this->compare('Bar/Baz', $this->divide('Foo/Bar/Baz', '/', 1, -1));
    }

    public function _divide4()
    {
        $this->compare(['Foo', 'Bar', 'Baz'], $this->divide('Foo/Bar/Baz', '/', 'all'));
    }

    public function _splitUpperCase()
    {
        $this->compare(['foo', 'Bar', 'Baz'], $this->splitUpperCase('fooBarBaz'));
    }
}
