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

class Base extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\Base',
        'methods' => 
        [
            'currentPath' => [],
            'illustrate'  => ['NEW_EXAMPLE_CONSTANT', 'value'],
            'layer'       => ['top'],
            'import'      => ['example.txt'],
            'host'        => [],
            'suffix'      => ['example'],
            'prefix'      => ['example'],
            'presuffix'   => ['example'], 
            'headers'     => ['example'],
            #'trace'      => ['example']
        ]
    ];
}
