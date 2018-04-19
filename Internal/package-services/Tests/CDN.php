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

class CDN extends UnitTest
{
    const unit =
    [
        'class'   => 'CDN',
        'methods' => 
        [
            'api'           => ['/site-address'],
            'getLibrary'    => ['jquery'],
            'searchQuery'   => ['bootstrap'],
            'get'           => ['script', 'jquery']
        ]
    ];
}
