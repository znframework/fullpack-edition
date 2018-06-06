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
    | The language of the Core structures.
    |
    */

    public $en = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'The External/Butchery/ directory does not contain any theme directory!',
        'butcher:cantMultipleExtractTheme'       => '% directory does not have the proper theme for multiple extraction!',
        'butcher:cantExtractTheme'               => 'The theme can not be extract! It may have been created before.',
        'butcher:extractThemeSuccess'            => 'Theme integration has been successfully completed.',
        'kernel:invalidOpenFunction'             => 'Your controller does not have a valid boot method! Please check your [openFunction] configuration under the Config/Routing.php path.',
        'zn:upgradeBackupNotFound'               => 'A valid upgrade backup was not found!'
    ];

    public $tr = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'External/Butchery/ dizini herhangi bir tema dizini içermiyor!',
        'butcher:cantMultipleExtractTheme'       => '% dizini çoklu çıkarma işlemine uygun tema yapısına sahip değil!',
        'butcher:cantExtractTheme'               => 'Tema çıkartılamıyor! Daha önce oluşturulmuş olabilir.',
        'butcher:extractThemeSuccess'            => 'Tema entegrasyonu başarı ile tamamlandı.',
        'kernel:invalidOpenFunction'             => 'Kontrolcünüz geçerli bir açılış yöntemi içermiyor! Lütfen Config/Routing.php yolu altında yer alan [openFunction] yapılandırmanızı kontrol edin.',
        'zn:upgradeBackupNotFound'               => 'Geçerli bir yükseltme yedeği bulunamadı!'
    ];
}
