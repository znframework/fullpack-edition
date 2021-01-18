<h2>ZN Framework Payment Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-payment
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/e-ticaret/odeme-sistemleri">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

# Request
$nestpay = Gateway::request('Nestpay');

$nestpay->clientId('123456')
        ->storeKey('19020000')
        ->cardType(1) # 1:visa 2:mastercard
        ->card('1234123412341234', 12, 18, '313')
        ->orderId(1)
        ->amount('10.00')
        ->returnUrl('Pay/response')
        ->send('test');
```
```php
# Response
$nestpay = Gateway::response('Nestpay');

if( $nestpay->isApproved() )
{
    echo 'İşlem başarılı.';
}
else
{
    echo $nestpay->error();
}
```
