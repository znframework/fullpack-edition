<?php namespace ZN\Services\Tests;
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

class Restful extends UnitTest
{
    const unit =
    [
        'class'   => 'Restful',
        'methods' => 
        [
            # 'contentType'   => ['json', 'utf-8'],
            'httpStatus'    => [200],
            'info'          => [NULL],
            'get'           => ['https://znframework.com']
        ]
    ];
}
