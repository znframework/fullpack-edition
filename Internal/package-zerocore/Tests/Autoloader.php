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

class Autoloader extends UnitTest
{
    const unit =
    [
        'class'   => 'ZN\Autoloader',
        'methods' => 
        [
            'run'                => ['ZN\Cryptography\Encode'],
            'standart'           => ['ZN\Cryptography\Encode'],
            'restart'            => [],
            'createClassMap'     => [],
            'getClassFileInfo'   => ['ZN\Cryptography\Encode'],
            'tokenClassFileInfo' => [COMMANDS_DIR . 'Example.php'],
            'tokenFileInfo'      => [COMMANDS_DIR . 'Example.php'],
            #'register'          => []
        ]
    ];
}
