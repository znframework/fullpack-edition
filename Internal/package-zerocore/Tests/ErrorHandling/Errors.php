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

class Errors extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\ErrorHandling\Errors',
        'methods' => 
        [
            'message' => ['Example Error Message'],
            'last'    => [],
            'log'     => ['Example Log'],
            'report'  => [],
            'handler' => [],
            #'trigger' => ['Example'],
            'restore' => []
        ]
    ];
}
