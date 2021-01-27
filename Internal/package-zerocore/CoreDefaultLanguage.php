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
        'zn:upgradeBackupNotFound'               => 'A valid upgrade backup was not found!',
        'benchmark:elapsedTime'                  => 'System Load Time',
        'benchmark:memoryUsage'                  => 'Memory Usage',
        'benchmark:maxMemoryUsage'               => 'Maximum Memory Usage',
        'benchmark:resultTable'                  => 'BENCHMARK RESULT TABLE',
        'benchmark:performanceTips'              => 'PERFORMANCE ENHANCING TIPS',
        'benchmark:laterProcess'                 => 'Use the following settings are recommended after completion of your project.',
        'benchmark:configAutoloader'             => 'Config/Autoloader.php',
        'benchmark:configHtaccess'               => 'Config/Htaccess.php',
        'benchmark:second'                       => 'Seconds',
        'benchmark:byte'                         => 'Bytes',
        'benchmark:countFile'                    => 'Count Load Files',
        'success'                                => 'The operation completed successfully.',
        'invalidCommand'                         => '`%` Invalid Command!',
        'emptyCommand'                           => 'The command parameter is empty!',
        'canNotCommandClass'                     => '[Command classes] can only be used with [console] commands!',
        'error'                                  => 'Operation failed!',
        'classError'                             => 'Error: `%` class was not found!',
        'controllerNameError'                    => 'Error: A controller can not be identified by the file `%` name!',
        'notFoundController'                     => 'Error: URL does not contain a valid controller information! `%` controller could not be found!',
        'callUserFuncArrayError'                 => 'Error: URL does not contain a valid function or method information! `%` method could not be found!',
        'notIsFileError'                         => 'Error: URL does not contain a valid path! `%` file could not be found!',
        'fileNotWrite'                           => 'Error: `%` file can not create! Please check the permits of file creation!',
        'undefinedFunction'                      => 'Error: Call to undefined function `%`!',
        'undefinedFunctionExtension'             => 'Error: `%` extension is not loaded! Install to use the `%` functions.',
        'invalidVersion'                         => 'Error: In order to use `%` methods need to be installed PHP version `#`!',
        'driverError'                            => '`%` driver not found!',
        'hashParameter'                          => '`%` parameter should contain the hash algos(md5, sha1) data type!',
        'emailParameter'                         => '`%` parameter should contain the email data type!',
        'objectParameter'                        => '`%` parameter should contain the object data type!',
        'resourceParameter'                      => '`%` parameter should contain the resource data type!',
        'callableParameter'                      => '`%` parameter should contain the callable data type!',
        'fileParameter'                          => '`%` parameter should contain the file data type!',
        'emptyParameter'                         => '`%` parameter should contain a value!',
        'emptyVariable'                          => '`%` variable should contain a value!',
        'charsetParameter'                       => '`%` parameter should contain a valid charset!',
        'invalidInput'                           => '`%` input information is invalid!',
        'typeHint'                               => 'Invalid parameter error! & parameter should be %!',
        'templateWizard'                         => 'Syntax error! Check the :, # and @ symbols.
                                                     The use of these symbols can be forgotten.
                                                     These symbols requires / prefix in normal use.',
        'invalidRequest'                         => 'Error: [Invalid Request!] Access via page URL is turned off.',
        'fileNotFound'                           => 'Error: `%` file was not found!',
        'folderNotFound'                         => 'Error: `%` folder was not found!',
        'fileAllready'                           => '`%` file already exists!',
        'folderAllready'                         => '`%` folder already exists!',
        'folderChangeDir'                        => '`%` Can not change the working directory!',
        'folderChangeName'                       => 'The name of the `%` file can not be changed!',
        'fileRemoteUpload'                       => '`%` file is not installed on the server!',
        'fileRemoteDownload'                     => '`%` file is not downloaded from the server!',
        'argumentSequence'                       => '`%` The argument must be such sequence'
    ];

    public $tr = 
    [
        'butcher:notFoundExternalButcheryThemes' => 'External/Butchery/ dizini herhangi bir tema dizini içermiyor!',
        'butcher:cantMultipleExtractTheme'       => '% dizini çoklu çıkarma işlemine uygun tema yapısına sahip değil!',
        'butcher:cantExtractTheme'               => 'Tema çıkartılamıyor! Daha önce oluşturulmuş olabilir.',
        'butcher:extractThemeSuccess'            => 'Tema entegrasyonu başarı ile tamamlandı.',
        'kernel:invalidOpenFunction'             => 'Kontrolcünüz geçerli bir açılış yöntemi içermiyor! Lütfen Config/Routing.php yolu altında yer alan [openFunction] yapılandırmanızı kontrol edin.',
        'zn:upgradeBackupNotFound'               => 'Geçerli bir yükseltme yedeği bulunamadı!',
        'benchmark:elapsedTime'                  => 'Sistem Yüklenme Süresi',
        'benchmark:memoryUsage'                  => 'Hafıza Kullanımı',
        'benchmark:maxMemoryUsage'               => 'Azami Hafıza Kullanımı',
        'benchmark:resultTable'                  => 'BENCHMARK SONUÇ TABLOSU',
        'benchmark:performanceTips'              => 'PERFORMANS ARTIRMA İPUÇLARI',
        'benchmark:laterProcess'                 => 'Projenizin tamamlanmasından sonra aşağıdaki ayarların kullanımı önerilir.',
        'benchmark:configAutoloader'             => 'Config/Autoloader.php',
        'benchmark:configHtaccess'               => 'Config/Htaccess.php',
        'benchmark:second'                       => 'Saniye',
        'benchmark:byte'                         => 'Bayt',
        'benchmark:countFile'                    => 'Yüklenen Dosya Sayısı',
        'success'                                => 'İşlem başarı ile tamamlandı.',
        'invalidCommand'                         => '`%` Geçersiz komut!',
        'emptyCommand'                           => 'Komut parametresi boş!',
        'canNotCommandClass'                     => '[Komut sınıfları] sadece [konsol] komutları ile kullanılabilir!',
        'error'                                  => 'İşlem başarısız.',
        'classError'                             => 'Hata: `%` sınıfı bulunamadı!',
        'controllerNameError'                    => 'Hata: Bir controller dosyası `%` kelimesi ile isimlendirilemez!',
        'notFoundController'                     => 'Hata: URL geçerli bir kontrolcü bilgisi içermiyor! `%` kontrolcüsü bulunamadı!',
        'callUserFuncArrayError'                 => 'Hata: URL geçerli fonksiyon veya metot bilgisi içermiyor! `%` metodu bulunamadı!',
        'notIsFileError'                         => 'Hata: URL geçerli bir yol içermiyor! `%` dosyası bulunamadı!',
        'fileNotWrite'                           => 'Hata: `%` dosyası oluşturulamıyor! Lütfen dosya oluşturma yetkilerini kontrol edin!',
        'undefinedFunction'                      => 'Hata: `%` fonksiyonu tanımlı değil!',
        'undefinedFunctionExtension'             => 'Hata: `%` eklentisi yüklü değil! `%` fonksiyonlarını kullanmak için yükleyiniz.',
        'invalidVersion'                         => 'Hata: `%` yöntemlerini kullanabilmeniz için en az `#` PHP sürümünün yüklü olması gerekmektedir!',
        'driverError'                            => '`%` sürücüsü bulunamadı!',
        'hashParameter'                          => '`%` parametresi şifreleme algoritmalarıdan(md5, sha1) birini içermelidir!',
        'emailParameter'                         => '`%` parametresi e-posta veri türü içermelidir!',
        'objectParameter'                        => '`%` parametresi object veri türü içermelidir!',
        'resourceParameter'                      => '`%` parametresi kaynak(resource) veri türü içermelidir!',
        'callableParameter'                      => '`%` parametresi çağrılabilir(callable) veri türü içermelidir!',
        'fileParameter'                          => '`%` parametresi dosya bilgisi içermelidir!',
        'emptyParameter'                         => '`%` parametresi bir değer içermelidir!',
        'emptyVariable'                          => '`%` değişkeni bir değer içermelidir!',
        'charsetParameter'                       => '`%` parametresi geçerli karakter seti içermelidir!',
        'invalidInput'                           => '`%` geçersiz girdi bilgisi!',
        'typeHint'                               => 'Geçersiz parametre hatası! & parametresi % türü olmalıdır!',
        'templateWizard'                         => 'Sözdizimi hatası! :, # and @ sembollerini kontrol edin.
                                                     Bu sembollerin kullanımı unutulmuş olabilir.
                                                     Bu semboller normal kullanımda / ön eki gerektirir.',
        'invalidRequest'                         => 'Hata: [Geçersiz İstek!] Sayfa URL üzerinden erişime kapatılmıştır.',
        'fileNotFound'                           => 'Hata: `%` dosyasi bulunamadi!',
        'folderNotFound'                         => 'Hata: `%` dizini bulunamadi!',
        'fileAllready'                           => '`%` dosyası zaten var!',
        'folderAllready'                         => '`%` dizini zaten var!',
        'folderChangeDir'                        => '`%` çalışma dizini olarak değiştirilemiyor!',
        'folderChangeName'                       => '`%` dosyasının adı değiştirilemiyor!',
        'fileRemoteUpload'                       => '`%` dosyası sunucuya yüklenemiyor!',
        'fileRemoteDownload'                     => '`%` dosyası sunucudan indirilemiyor!',
        'argumentSequence'                       => '`%` Argüman dizilimi böyle olmalıdır'
    ];
}
