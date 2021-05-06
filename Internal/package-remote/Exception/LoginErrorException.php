<?php namespace ZN\Remote\Exception;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Ability\Exclusion;

class LoginErrorException extends \InvalidArgumentException
{
    use Exclusion;

    const lang =
    [
        'en' => 'User and password information is incorrect!',
        'tr' => 'Kullanıcı ve şifre bilgisi hatalı!'
    ];
}
