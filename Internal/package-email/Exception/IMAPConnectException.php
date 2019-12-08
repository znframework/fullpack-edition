<?php namespace ZN\Email\Exception;
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

class IMAPConnectException extends IOException
{
    use Exclusion;

    const lang = 
    [
        'en' => 'IMAP connection error!',
        'tr' => 'IMAP bağlantı hatası!'
    ];
}
