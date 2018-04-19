<?php namespace ZN\Crontab\Tests;
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

class Crontab extends UnitTest
{
    const unit =
    [
        'class'   => 'Crontab',
        'methods' => 
        [
            'perminute'  => [1],
            'controller' => ['example/controller'],
            'command'    => ['example:command'],
            'wget'       => ['http://localhost/example/controller'],
            'list'       => [],
            'remove'     => [NULL]
        ]
    ];
}
