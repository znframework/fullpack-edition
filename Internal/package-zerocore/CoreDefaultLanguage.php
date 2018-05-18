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
 * Provides predefined language content for core classes.
 */
class CoreDefaultLanguage
{
    /*
    |--------------------------------------------------------------------------
    | Butcher
    |--------------------------------------------------------------------------
    |
    | The language of the Butcher class.
    |
    */

    public $en = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'The External/Butchery/ directory does not contain any theme directory!',
        'butcher:cantMultipleExtractTheme'       => '% directory does not have the proper theme for multiple extraction!',
        'butcher:cantExtractTheme'               => 'The theme can not be extract! It may have been created before.',
        'butcher:extractThemeSuccess'            => 'Theme integration has been successfully completed.'
    ];

    public $tr = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'External/Butchery/ dizini herhangi bir tema dizini içermiyor!',
        'butcher:cantMultipleExtractTheme'       => '% dizini çoklu çıkarma işlemine uygun tema yapısına sahip değil!',
        'butcher:cantExtractTheme'               => 'Tema çıkartılamıyor! Daha önce oluşturulmuş olabilir.',
        'butcher:extractThemeSuccess'            => 'Tema entegrasyonu başarı ile tamamlandı.'
    ];
}
