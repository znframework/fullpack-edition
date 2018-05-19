<?php namespace ZN\Tests\Controller;
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

class Factory extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\Controller\Factory',
        'methods' => 
        [
            'call' => ['example', ['p1', 'p2']]
        ]
    ];
}
