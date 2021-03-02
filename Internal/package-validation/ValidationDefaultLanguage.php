<?php namespace ZN\Validation;
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
class ValidationDefaultLanguage
{
    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | The language of the Validation library.
    |
    */

    public $en = 
    [
        'validation:required'               => ':name field blank, impenetrable!',
        'validation:matchPassword'          => 'Passwords do not match!',
        'validation:match'                  => ':name datas do not match!',
        'validation:email'                  => ':name field is not valid email address!',
        'validation:url'                    => ':name field is invalid url information!',
        'validation:identity'               => ':name domain credentials are not valid!',
        'validation:noSpecialChar'          => ':name field can not contain special characters!',
        'validation:numeric'                => ':name area should consist only of numbers!',
        'validation:alpha'                  => ':name area should consist only of alpha!',
        'validation:alnum'                  => ':name area should consist only of alphanumeric!',
        'validation:phone'                  => ':name field should contain only information phone!',
        'validation:maxchar'                => 'most of the space :name more :p1 characters long!',
        'validation:minchar'                => ':name area of ​​at least :p1 characters long!',
        'validation:captcha'                => 'Security code is wrong!',
        'validation:answer'                 => 'Security answer is wrong!',
        'validation:pattern'                => 'Data patterns with incompatible :name!',
        'validation:between'                => 'The :name field must be between [:p1] - [:p2]!',
        'validation:betweenBoth'            => 'The :name field must be between or equal [:p1] - [:p2]!',
        'validation:card'                   => ':name field contains invalid card number!',
        'validation:cvc'                    => ':name field contains invalid security number!',
        'validation:cardDate'               => ':name field contains invalid date information!'
    ];

    public $tr = 
    [
        'validation:required'               => ':name alanı boş geçilemez!',
        'validation:matchPassword'          => 'Şifreler uyumsuz!',
        'validation:match'                  => ':name bilgileri uyumsuz!',
        'validation:email'                  => ':name alanı geçersiz posta adresidir!',
        'validation:url'                    => ':name alanı geçersiz url bilgisidir!',
        'validation:identity'               => ':name alanı geçersiz kimlik bilgisidir!',
        'validation:noSpecialChar'          => ':name alanı özel karakter içeremez!',
        'validation:numeric'                => ':name alanı sadece sayılardan oluşmalıdır!',
        'validation:alpha'                  => ':name alanı sadece harflerden oluşmalıdır!',
        'validation:alnum'                  => ':name alanı sadece sayı ve harflerden oluşmalıdır!',
        'validation:phone'                  => ':name alanı sadece telefon bilgisi içermelidir!',
        'validation:maxchar'                => ':name alanı en fazla :p1 karakterden oluşmalıdır!',
        'validation:minchar'                => ':name alanı en az :p1 karakterden oluşmalıdır!',
        'validation:captcha'                => 'Güvenlik kodu hatalı!',
        'validation:answer'                 => 'Güvenlik cevabı hatalı!',
        'validation:pattern'                => ':name verisi ile desen uyumsuz!',
        'validation:between'                => ':name alanı [:p1] - [:p2] değerleri arasında olmalıdır!',
        'validation:betweenBoth'            => ':name alanı [:p1] - [:p2] değerleri veya arasında olmalıdır!',
        'validation:card'                   => ':name alanı geçersiz kart numarası içermektedir!',
        'validation:cvc'                    => ':name alanı geçersiz güvenlik numarası içermektedir!',
        'validation:cardDate'               => ':name alanı geçersiz tarih bilgisi içermektedir!'
    ];
}
