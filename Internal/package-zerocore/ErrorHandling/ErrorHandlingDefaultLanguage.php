<?php namespace ZN\ErrorHandling;
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
class ErrorHandlingDefaultLanguage
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
        'type'    => 'Type',
        'line'    => 'Line',
        'message' => 'Error',
        'file'    => 'File',
        'trace'   => 'Trace',
        'request' => 'Request',
        'runtime' => 'Runtime',
        'solution'=> 'Suggested Solution',
        'get'     => 'GET',
        'post'    => 'POST',
        'files'   => 'FILES',
        'noData'  => 'No data.',
        'syntaxSuggestion' => 'Check the selected line and the preceding line for a missing semicolon, comma, bracket or quote.',
        'delimiterSuggestion' => 'Also match opening/closing brackets, braces and quotes in the surrounding block.',
        'delimiterDetailSuggestion' => 'The opening `%open%` does not match the closing `%close%`. Check the block starting at line %line%.',
        'variableSuggestion' => 'Make sure the variable is defined before use and is available in the current scope.',
        'variableDetailSuggestion' => '`%value%` variable is being used before it is defined in the current scope.',
        'arraySuggestion' => 'Verify the array key/offset exists before reading it and validate the incoming data shape.',
        'arrayDetailSuggestion' => '`%value%` array key/offset is being read, but it does not exist in the current data.',
        'propertySuggestion' => 'Verify the object is initialized and that the property name belongs to its actual class.',
        'functionSuggestion' => 'Check the function name, its namespace/import and the package that provides it.',
        'methodSuggestion' => 'Verify the object type and that the called method is public and available on that class.',
        'methodDetailSuggestion' => '`%value%` method cannot be called on the current object/class.',
        'classSuggestion' => 'Check the class name, namespace/import and autoloader map.',
        'classDetailSuggestion' => '`%value%` class/interface/trait could not be loaded.',
        'argumentSuggestion' => 'Compare the method signature with the call: required argument count, order and optional values.',
        'typeSuggestion' => 'Check the expected parameter/return type and validate or cast the value before this call.',
        'operandSuggestion' => 'Arithmetic operators require compatible scalar values. Do not add, subtract, multiply or divide an array; select the required item or aggregate it first.',
        'conversionSuggestion' => 'Do not implicitly convert arrays or arbitrary objects to strings. Select the required scalar value or use an explicit, meaningful conversion.',
        'offsetSuggestion' => 'Validate the value type before reading or writing an array/string offset. Ensure the expected array or string is initialized first.',
        'iterableSuggestion' => 'foreach requires an array or Traversable object. Validate the value and use an empty array fallback when iteration is optional.',
        'countSuggestion' => 'count() requires an array or Countable object. Validate the value or use an empty array fallback before counting.',
        'numericSuggestion' => 'Validate that the input is numeric before calculation; normalize locale-specific decimal separators when necessary.',
        'valueSuggestion' => 'Validate the method input against its allowed range, enum/value set and empty-value rules before this call.',
        'readonlySuggestion' => 'Readonly properties can only be initialized once from their declaring class. Create a new object instead of modifying the existing property.',
        'typeMismatchSuggestion' => '`%function%` method argument #%argument% (`$%parameter%`) must be `%expected%`; `%actual%` was passed.',
        'arrayTypeSuggestion' => 'This method expects one value, not an array. Pass the relevant array item; use implode() only when joining values is logically correct.',
        'nullTypeSuggestion' => 'A null value was passed. Provide a fallback value or validate the value before this call.',
        'scalarTypeSuggestion' => 'Convert the value only when the conversion matches the business rule; otherwise correct the value at its source.',
        'nullSuggestion' => 'Check the value for null before accessing a property, method, array offset or string offset.',
        'mathSuggestion' => 'Validate the divisor and numeric input before performing the calculation.',
        'fileSuggestion' => 'Verify the file path, filename, deployment package and include/require location.',
        'fileDetailSuggestion' => '`%value%` file could not be opened or included.',
        'permissionSuggestion' => 'Verify the filesystem path exists and the web server user has read/write permission.',
        'databaseSuggestion' => 'Check the query table/column names and the current database schema.',
        'databaseDetailSuggestion' => '`%value%` database identifier could not be found by the query.',
        'databaseConnectionSuggestion' => 'Check database host, credentials, selected database and the current connection configuration.',
        'databaseConstraintSuggestion' => 'Check unique, foreign-key and required-field constraints against the data being saved.',
        'memorySuggestion' => 'Inspect recursive/large loops and payload sizes; release unnecessary data before this operation.',
        'timeoutSuggestion' => 'Check the slow operation, external service response and configured execution timeout.',
        'networkSuggestion' => 'Check the endpoint URL, DNS/SSL settings, credentials and the remote service response.',
        'jsonSuggestion' => 'Validate the JSON payload and character encoding before decoding or encoding it.',
        'regexSuggestion' => 'Check the regular-expression delimiter, escaping and pattern syntax.',
        'uploadSuggestion' => 'Check upload size limits, temporary directory availability and the uploaded file error code.',
        'sessionSuggestion' => 'Check whether the session is started and whether the requested session key is set.',
        'genericSuggestion' => 'Review the selected line together with the surrounding source lines.',
        'wizardSuggestion' => 'This error is in a Wizard (.wizard.php) file. Check the Wizard directive/block syntax around the highlighted line, not plain PHP syntax.',
        'wizardImbalanceSuggestion' => '%label%: %open% appears %openCount%×, %close% appears %closeCount%× — mismatch.',
        'wizardStrayCloseTagSuggestion' => 'This file contains a literal `?>`. Wizard wraps the whole file as one PHP block, so this closes it early and breaks everything after it. Remove it.',
        'wizardUnclosedDirectiveSuggestion' => '%directive% is not closed with `:` here. Fix: write %directiveWithColon%, or use `{{ ... }}` instead.'
    ];

    public $tr = 
    [
        'type'    => 'Tür',
        'line'    => 'Satır',
        'message' => 'Hata',
        'file'    => 'Dosya',
        'trace'   => 'İz',
        'request' => 'İstek',
        'runtime' => 'Ortam',
        'solution'=> 'Çözüm Önerisi',
        'get'     => 'GET',
        'post'    => 'POST',
        'files'   => 'DOSYALAR',
        'noData'  => 'Veri yok.',
        'syntaxSuggestion' => 'Seçili satırı ve bir önceki satırı eksik noktalı virgül, virgül, parantez veya tırnak açısından kontrol edin.',
        'delimiterSuggestion' => 'Bulunduğu bloktaki açılış/kapanış parantezlerini, süslü parantezleri ve tırnakları eşleştirin.',
        'delimiterDetailSuggestion' => 'Açılan `%open%` karakteri kapanan `%close%` karakteriyle eşleşmiyor. %line%. satırdan başlayan bloğu kontrol edin.',
        'variableSuggestion' => 'Değişkenin kullanılmadan önce tanımlandığından ve mevcut kapsamda erişilebilir olduğundan emin olun.',
        'variableDetailSuggestion' => '`%value%` değişkeni mevcut kapsamda tanımlanmadan önce kullanılmaya çalışılıyor.',
        'arraySuggestion' => 'Okumadan önce dizi anahtarının/ofsetinin varlığını ve gelen verinin yapısını doğrulayın.',
        'arrayDetailSuggestion' => '`%value%` dizi anahtarı/ofseti okunuyor; ancak mevcut veride bulunmuyor.',
        'propertySuggestion' => 'Objenin başlatıldığını ve property adının objenin gerçek sınıfında bulunduğunu kontrol edin.',
        'functionSuggestion' => 'Fonksiyon adını, namespace/import kullanımını ve fonksiyonu sağlayan paketi kontrol edin.',
        'methodSuggestion' => 'Objenin türünü, çağrılan metodun public olduğunu ve o sınıfta bulunduğunu kontrol edin.',
        'methodDetailSuggestion' => '`%value%` metodu mevcut obje/sınıf üzerinde çağrılamıyor.',
        'classSuggestion' => 'Sınıf adını, namespace/import kullanımını ve autoloader eşlemesini kontrol edin.',
        'classDetailSuggestion' => '`%value%` sınıfı/interface’i/trait’i yüklenemedi.',
        'argumentSuggestion' => 'Metot imzası ile çağrıyı; zorunlu parametre sayısı, sırası ve opsiyonel değerler açısından karşılaştırın.',
        'typeSuggestion' => 'Beklenen parametre/dönüş tipini kontrol edin; bu çağrıdan önce değeri doğrulayın veya dönüştürün.',
        'operandSuggestion' => 'Aritmetik operatörler uyumlu skaler değerler gerektirir. Dizi üzerinde toplama, çıkarma, çarpma veya bölme yapmayın; gerekli öğeyi seçin ya da önce toplulaştırın.',
        'conversionSuggestion' => 'Dizi veya rastgele objeleri örtük olarak stringe dönüştürmeyin. Gerekli skaler değeri seçin ya da anlamlı ve açık bir dönüşüm uygulayın.',
        'offsetSuggestion' => 'Dizi/string ofsetini okumadan veya yazmadan önce değerin türünü doğrulayın. Beklenen dizi veya stringin başlatıldığından emin olun.',
        'iterableSuggestion' => 'foreach dizi veya Traversable obje ister. Değeri doğrulayın; iterasyon opsiyonelse boş dizi varsayılanı kullanın.',
        'countSuggestion' => 'count() dizi veya Countable obje ister. Saymadan önce değeri doğrulayın ya da boş dizi varsayılanı kullanın.',
        'numericSuggestion' => 'Hesaplamadan önce girdinin sayısal olduğunu doğrulayın; gerekiyorsa bölgesel ondalık ayraçlarını normalize edin.',
        'valueSuggestion' => 'Bu çağrıdan önce metod girdisini izin verilen aralık, enum/değer kümesi ve boş değer kurallarına göre doğrulayın.',
        'readonlySuggestion' => 'Readonly property yalnızca tanımlandığı sınıftan bir kez başlatılabilir. Mevcut property’yi değiştirmek yerine yeni obje oluşturun.',
        'typeMismatchSuggestion' => '`%function%` metodunun #%argument% (`$%parameter%`) parametresi `%expected%` türünde olmalı; `%actual%` gönderilmiş.',
        'arrayTypeSuggestion' => 'Bu metot dizi değil tek bir değer bekliyor. Diziden ilgili öğeyi gönderin; ancak değerleri birleştirmek iş kuralına uygunsa implode() kullanın.',
        'nullTypeSuggestion' => 'Null değer gönderilmiş. Bu çağrıdan önce varsayılan değer verin veya değeri doğrulayın.',
        'scalarTypeSuggestion' => 'Dönüşümü yalnızca iş kuralına uygunsa uygulayın; aksi hâlde veriyi oluştuğu noktada doğru tipe getirin.',
        'nullSuggestion' => 'Property, metot, dizi ofseti veya string ofsetine erişmeden önce değerin null olmadığını kontrol edin.',
        'mathSuggestion' => 'Hesaplamadan önce böleni ve sayısal girdiyi doğrulayın.',
        'fileSuggestion' => 'Dosya yolunu, dosya adını, dağıtım paketini ve include/require konumunu kontrol edin.',
        'fileDetailSuggestion' => '`%value%` dosyası açılamadı veya include edilemedi.',
        'permissionSuggestion' => 'Dosya yolunun var olduğunu ve web sunucusu kullanıcısının okuma/yazma yetkisini kontrol edin.',
        'databaseSuggestion' => 'Sorgudaki tablo/kolon adlarını ve güncel veritabanı şemasını kontrol edin.',
        'databaseDetailSuggestion' => '`%value%` veritabanı tanımlayıcısı sorguda bulunamadı.',
        'databaseConnectionSuggestion' => 'Veritabanı hostu, kimlik bilgileri, seçili veritabanı ve aktif bağlantı ayarlarını kontrol edin.',
        'databaseConstraintSuggestion' => 'Kaydedilen veri için benzersiz, foreign-key ve zorunlu alan kısıtlarını kontrol edin.',
        'memorySuggestion' => 'Özyinelemeli/büyük döngüleri ve payload boyutlarını inceleyin; gereksiz veriyi işlemden önce serbest bırakın.',
        'timeoutSuggestion' => 'Yavaş işlemi, dış servis yanıtını ve tanımlı çalışma zaman aşımını kontrol edin.',
        'networkSuggestion' => 'Uç nokta URL’sini, DNS/SSL ayarlarını, kimlik bilgilerini ve uzak servis yanıtını kontrol edin.',
        'jsonSuggestion' => 'Decode veya encode öncesinde JSON içeriğini ve karakter kodlamasını doğrulayın.',
        'regexSuggestion' => 'Düzenli ifade ayıracını, escape kullanımını ve pattern sözdizimini kontrol edin.',
        'uploadSuggestion' => 'Yükleme boyut limitlerini, geçici dizinin durumunu ve yüklenen dosyanın hata kodunu kontrol edin.',
        'sessionSuggestion' => 'Session’ın başlatıldığını ve istenen session anahtarının tanımlı olduğunu kontrol edin.',
        'genericSuggestion' => 'Seçili satırı çevresindeki kaynak satırlarıyla birlikte inceleyin.',
        'wizardSuggestion' => 'Bu hata bir Wizard (.wizard.php) dosyasında oluşuyor. İşaretli satırın çevresindeki Wizard direktif/blok kullanımını kontrol edin, düz PHP söz dizimini değil.',
        'wizardImbalanceSuggestion' => '%label%: %open% %openCount% kez, %close% %closeCount% kez geçiyor — eşleşmiyor.',
        'wizardStrayCloseTagSuggestion' => 'Bu dosyada düz bir `?>` var. Wizard tüm dosyayı tek bir PHP bloğu sayar, bu yüzden bu ifade PHP modunu erken kapatıp sonrasını bozar. Kaldırın.',
        'wizardUnclosedDirectiveSuggestion' => '%directive% burada `:` ile kapanmıyor. Düzeltme: %directiveWithColon% yazın, ya da `{{ ... }}` kullanın.'
    ];
}
