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

class Classes extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\Classes',
        'methods' => 
        [
            'reflection'         => ['ZN\Database\DB'],
            'isParent'           => ['ZN\Database\DB', 'select'],
            'methodExists'       => ['ZN\Database\DB', 'select'],
            'propertyExists'     => ['ZN\Database\DB', 'table'],
            'methods'            => ['ZN\Database\DB'],
            'vars'               => ['ZN\Database\DB'],
            'name'               => ['ZN\Database\DB'],
            'declared'           => [],
            'declaredInterfaces' => [],
            'declaredTraits'     => [],
            'onlyName'           => ['ZN\Database\DB'],
            'class'              => ['ZN\Database\DB']
        ]
    ];

    public function _isRelation()
    {
        $db = new \ZN\Database\DB;

        $this->compare(true, $this->isRelation('ZN\Database\DB', $db));
    }
}
