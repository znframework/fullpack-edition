<?php namespace ZN;
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
class CoreDefaultLanguage
{
    /*
    |--------------------------------------------------------------------------
    | Upload
    |--------------------------------------------------------------------------
    |
    | The language of the Upload library.
    |
    */

    public $en = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'The External/Butchery/ directory does not contain any theme directory!',
        'butcher:cantExtractTheme'               => 'The theme can not be extract! It may have been created before.',
        'butcher:extractThemeSuccess'            => 'Theme integration has been successfully completed.'
    ];

    public $tr = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'External/Butchery/ dizini herhangi bir tema dizini içermiyor!',
        'butcher:cantExtractTheme'               => 'Tema çıkartılamıyor! Daha önce oluşturulmuş olabilir.',
        'butcher:extractThemeSuccess'            => 'Tema entegrasyonu başarı ile tamamlandı.'
    ];
}
