<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

/**
 * Default Configuration
 * 
 * Enabled when the language file can not be accessed.
 */
class ConsoleDefaultLanguage
{
    /*
    |--------------------------------------------------------------------------
    | Console
    |--------------------------------------------------------------------------
    |
    | The language of the Console library.
    |
    */
    
    public $en = 
    [
        'upgradeSuccess'   => 'The upgrade was successfully completed.',
        'alreadyVersion'   => 'The version you are using is already up to date!',
        'composerUpdate'   => 'Can not upgrade! Please run the composer update command from the console.'
     
    ];
    
    public $tr = 
    [
        'upgradeSuccess'   => 'Yükseltme işlemi başarı ile tamamlandı.',
        'alreadyVersion'   => 'Kullandığınız sürüm zaten güncel!',
        'composerUpdate'   => 'Yükseltme işlemi yapılamıyor! Lütfen konsoldan composer update komutunu çalıştırarak deneyin.'
    ];
}
