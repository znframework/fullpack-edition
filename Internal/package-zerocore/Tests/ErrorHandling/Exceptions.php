<?php namespace ZN\Tests\ErrorHandling;
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

class Exceptions extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\ErrorHandling\Exceptions',
        'methods' => 
        [
            #'throws'   => ['Example Error Message'],
            #'table'    => [],
            #'continue' => ['message', 'file', 5],
            'restore'  => [],
            'handler'  => []
        ]
    ];
}
